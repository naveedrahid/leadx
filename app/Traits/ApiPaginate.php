<?php

namespace App\Traits;

trait ApiPaginate
{
	public function paginate($data)
	{
		return [
			'current_page' => $data->currentPage(),
			'last_page' => $data->lastPage(),
			'per_page' => $data->perPage(),
			'from' => $data->firstItem() ?? 0,
			'to' => $data->lastItem() ?? 0,
			'count' => $data->count(),
			'total' => $data->total(),
			'has_more_pages' => $data->hasMorePages()
		];
	}
}