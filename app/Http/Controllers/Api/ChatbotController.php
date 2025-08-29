<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatbotRequest;
use App\Http\Requests\QAPairRequest;
use App\Http\Requests\StoreQAPairRequest;
use App\Http\Resources\ChatbotResource;
use App\Http\Resources\QAPairResource;
use App\Models\Chatbot;
use App\Models\QAPair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    // public function index(Request $request)
    // {
    //     $user = $request->user();
    //     if (! $user) {
    //         return response()->json(['message' => 'Unauthenticated.'], 401);
    //     }

    //     $bots = Chatbot::query()
    //         ->where('user_id', $user->id)
    //         ->latest()
    //         ->get();

    //     return ChatbotResource::collection($bots);
    // }

    // public function store(ChatbotRequest $request)
    // {
    //     $data = $request->validated();
    //     $user = $request->user();

    //     $base = Str::slug($data['name']);
    //     $slug = $base;
    //     $i = 1;
    //     while (Chatbot::where('slug', $slug)->exists()) {
    //         $slug = $base . '-' . (++$i);
    //     }

    //     $bot = Chatbot::create([
    //         'user_id'       => $user->id,
    //         'name'          => $data['name'],
    //         'slug'          => $slug,
    //         'is_active'     => $request->boolean('is_active', true),
    //         'system_prompt' => $data['system_prompt'] ?? null,
    //     ]);

    //     return (new ChatbotResource($bot->loadCount('qaPairs')))
    //         ->response()->setStatusCode(201);
    // }

    // public function storeBulk(StoreQAPairRequest $request, Chatbot $chatbot)
    // {
    //     $data = $request->validated();

    //     DB::transaction(function () use ($chatbot, $data) {
    //         $rows = collect($data['qa_pairs'])->map(fn($q) => [
    //             'chatbot_id' => $chatbot->id,
    //             'question'   => $q['question'],
    //             'answer'     => $q['answer'],
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ])->all();

    //         if (! empty($rows)) {
    //             $chatbot->qaPairs()->insert($rows);
    //         }
    //     });

    //     $chatbot->load(['qaPairs' => fn($q) => $q->latest()]);
    //     return response()->json([
    //         'message' => 'Q/A pairs saved',
    //         'items'   => QAPairResource::collection($chatbot->qaPairs),
    //         'count'   => $chatbot->qaPairs()->count(),
    //     ], 201);
    // }

    // public function storeQA(QAPairRequest $request, Chatbot $chatbot)
    // {
    //     $qa = $chatbot->qaPairs()->create($request->validated());
    //     return (new QAPairResource($qa))->response()->setStatusCode(201);
    // }

    public function index(Request $request)
    {
        $bots = Chatbot::query()
            ->where('user_id', Auth::id())
            ->withCount('qaPairs')
            ->latest('id')
            ->get();

        return ChatbotResource::collection($bots);
    }

    public function store(ChatbotRequest $request)
    {
        $request->validate(['website_id' => 'required|integer']);

        $allowed = [
            'name',
            'is_active',
            'system_prompt',
            'bubble_message',
            'welcome_message',
            'connect_message',
            'offline_message',
            'interaction_type',
            'language',
            'do_not_go_beyond',
            'model',
            'temperature',
            'top_k',
            'confidence_threshold',
            'avatar_type',
            'avatar_url',
            'logo_url',
            'color_accent',
            'show_logo',
            'show_datetime',
            'transparent_trigger',
            'trigger_avatar_size',
            'position',
            'footer_link',
            'custom_css',
            'settings',
            'iframe_width',
            'iframe_height',
            'domain_allowlist',
            'moderation_on'
        ];

        $payload = collect($request->only($allowed))->filter(fn($v) => !is_null($v))->all();

        $bot = Chatbot::create(array_merge([
            'user_id'      => Auth::id(),
            'website_id'   => (int) $request->website_id,
            'slug'         => Chatbot::makeUniqueSlug($request->name),
            'public_token' => (string) Str::uuid(),
        ], $payload));

        $bot->loadCount('qaPairs');

        return (new ChatbotResource($bot))->response()->setStatusCode(201);
    }

    public function storeQA(Request $request, Chatbot $chatbot)
    {
        $this->authorizeBot($chatbot);

        $data = $request->validate([
            'question'   => ['required', 'string'],
            'answer'     => ['required', 'string'],
            'is_active'  => ['nullable', 'boolean'],
            'priority'   => ['nullable', 'integer'],
            'tags'       => ['nullable', 'array'],
        ]);

        $row = QAPair::create([
            'chatbot_id'  => $chatbot->id,
            'website_id'  => $chatbot->website_id,
            'user_id'     => Auth::id(),
            'question'    => $data['question'],
            'answer'      => $data['answer'],
            'is_active'   => $data['is_active'] ?? true,
            'priority'    => $data['priority'] ?? 0,
            'tags'        => $data['tags'] ?? null,
            'source_type' => 'manual',
        ]);

        return response()->json(['data' => $row], 201);
    }

    public function storeBulk(Request $request, Chatbot $chatbot)
    {
        $this->authorizeBot($chatbot);

        $validated = $request->validate([
            'qa_pairs'             => ['required', 'array', 'min:1'],
            'qa_pairs.*.question'  => ['required', 'string'],
            'qa_pairs.*.answer'    => ['required', 'string'],
        ]);

        $now = now();
        $uid = Auth::id();
        $rows = array_map(function ($qa) use ($chatbot, $uid, $now) {
            return [
                'chatbot_id'  => $chatbot->id,
                'website_id'  => $chatbot->website_id,
                'user_id'     => $uid,
                'question'    => $qa['question'],
                'answer'      => $qa['answer'],
                'is_active'   => true,
                'priority'    => 0,
                'source_type' => 'manual',
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }, $validated['qa_pairs']);

        DB::table('qa_pairs')->insert($rows);

        return response()->json(['data' => ['inserted' => count($rows)]]);
    }

    protected function authorizeBot(Chatbot $bot): void
    {
        abort_if(Auth::id() !== $bot->user_id, 403, 'Unauthorized chatbot access');
    }

        public function uploadLogo(Request $request)
    {
        $request->validate([
            'file' => ['required','file','mimes:jpg,jpeg,png,webp,svg','max:2048'],
        ]);

        $path = $request->file('file')->store('chatbots', 'public');
        return response()->json([
            'url'  => Storage::disk('public')->url($path),
            'path' => $path,
        ]);
    }
}
