<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Services\UploadService;

abstract class AbstractEntityCRUDController extends Controller
{
	use TraitEntityCRUDShow;
	use TraitEntityCRUDCreateEdit;
	use TraitEntityCRUDStoreUpdate;

	use TraitEntityM2M;
	use TraitEntityAttachment;
	use TraitEntityComment;
	use TraitEntityStatus;
	use TraitEntityFormula;

	protected $type;
	protected $data;
	protected $branchName = 'entities';
	protected $disk = 'json';
	protected $r_fileName = 'props.json';
	public function __construct(
		protected UploadService $uploadService,
	) {
	}

	public function getType()
	{
		return $this->type;
	}
}
