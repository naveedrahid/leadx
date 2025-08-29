# Phase 1 — Chatbot Registry (Spec)

## Scope
- Chatbot create/update/delete/list
- Har chatbot ke liye 1:1 OpenAI **Assistant** aur **Vector Store** banana/maintain karna
- Model selection (catalog) + plan gating
- Instructions/temperature store karna
- Ownership (multi-tenant isolation)
- Basic audit logging

---

## Terminology
- **Chatbot** = LeadX entity jisko user create karta hai
- **Assistant** = OpenAI side resource
- **Vector Store** = OpenAI side file store for retrieval

---

## Data Models (outline only — DDL next phase)
### `chatbots`
- `id` (pk)
- `user_id` (owner)
- `name` (string 3..80)
- `model_key` (fk to models.key; e.g., `gpt-4o`, `gpt-4o-mini`)
- `assistant_id` (OpenAI)
- `vector_store_id` (OpenAI)
- `instructions` (text; optional)
- `temperature` (float; default 0.3; min 0 max 1)
- `status` (enum: `active`, `disabled`, `error`, `deleting`)
- `visibility` (enum: `private`, `unlisted`)  // public UI later
- `created_at`, `updated_at`

### `models` (seeded catalog)
- `id`
- `key` (unique; e.g., `gpt-4o`)
- `display_name` (e.g., `ChatGPT 4o (Flagship)`)
- `family` (`gpt-4o`, `gpt-4o-mini`, etc.)
- `is_enabled` (bool)
- `default_temp` (float)
- `plan_min` (enum: `free`, `pro`, `enterprise`)

### `audit_logs`
- `id`
- `actor_id` (user/admin)
- `action` (e.g., `chatbot.create`, `chatbot.update`, `chatbot.delete`, `chatbot.rotate_assistant`)
- `entity` (e.g., `chatbot`)
- `entity_id`
- `meta_json`
- `created_at`

---

## OpenAI Resource Mapping
- **Per Chatbot**: create one **Assistant** and one **Vector Store**
- Attach Vector Store to Assistant’s tools (`file_search`)
- On `model_key` change → Assistant update
- On delete → Assistant + Vector Store **hard delete** (best effort retry)

---

## API Contracts (no code yet)

### 1) Create Chatbot
`POST /api/chatbots`
```json
{
  "name": "LeadX Support Bot",
  "model_key": "gpt-4o-mini",
  "instructions": "You are a helpful support bot...",
  "temperature": 0.3,
  "visibility": "private"
}
