php

namespace App\Repository\Eloquent\NEW_NAMESPACE;

use App\Repository\Eloquent\BaseRepository;
use App\Repository\Interfaces\INTERFACE_NAMESPACE;
use App\Models\MODEL_NAMESPACE;

class NEW_CLASS extends BaseRepository implements INTERFACE_NAME{

    protected $model;

    public function __construct(MODEL_NAME $model)
    {
        $this->model = $model;   
    }
}