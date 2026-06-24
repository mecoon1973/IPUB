<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'model:create {modelName} {nameService} {module} {createRequest=false} {createController=false} {createView=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Model, Repository, Service, Filter, Request';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $modelName = $this->argument('modelName');
        $moduleName = $this->argument('module');
        $nameService = $this->argument('nameService');
        $moduleNameUpcase = ucfirst($this->argument('module'));
        $createRequest = $this->argument('createRequest') === "true";
        $createController = $this->argument('createController') === "true";
        $createView = $this->argument('createView') === "true";
        $lowerModelName = \strtolower($modelName);

        $directoryModel = 'Modules\\'.$moduleName.'\\Model';
        if (! is_dir($directoryModel)) {
            mkdir($directoryModel, 0755, true);
        }

        $modelFile = fopen($directoryModel.'\\'.$modelName.'.php',"w");
        $modelText = '<?php

namespace Modules\\'.$moduleNameUpcase.'\Model;

use Core\Model\Model;
/**
 *
 */
class '.$modelName.' extends Model {
    protected $connection = "olm";

    protected $table = "ipub_'.$lowerModelName.'";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
    ];

    protected $attributes = [
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}';
        fwrite($modelFile, $modelText);
        fclose($modelFile);


        $directoryRepository = 'Modules\\'.$moduleName.'\\Repository';
        if (! is_dir($directoryRepository)) {
            mkdir($directoryRepository, 0755, true);
        }
        if (! is_dir($directoryRepository."\\Impl")) {
            mkdir($directoryRepository."\\Impl", 0755, true);
        }

        $repositoryFile = fopen($directoryRepository.'\\'.$nameService.'Repository'.'.php',"w");
        $repositoryText = '<?php

namespace Modules\\'.$moduleNameUpcase.'\Repository;

use Core\Repository\IBaseRepository;

use Modules\\'.$moduleNameUpcase.'\Model\\'.$modelName.';

/**
 * @extends IBaseRepository<'.$modelName.'>
 */
interface '.$nameService.'Repository extends IBaseRepository {

}
';
        fwrite($repositoryFile, $repositoryText);
        fclose($repositoryFile);

        $repositoryFileImpl = fopen($directoryRepository.'\Impl\\'.$nameService.'RepositoryImpl'.'.php',"w");
        $repositoryTextImpl = '<?php

namespace Modules\\'.$moduleNameUpcase.'\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\\'.$moduleNameUpcase.'\Repository\\'.$nameService.'Repository;
use Modules\\'.$moduleNameUpcase.'\Model\\'.$modelName.';


class '.$nameService.'RepositoryImpl extends BaseRepository implements '.$nameService.'Repository {
    public function getModel() {
        return '.$modelName.'::class;
    }

}
';
        fwrite($repositoryFileImpl, $repositoryTextImpl);
        fclose($repositoryFileImpl);

        $directoryService = 'Modules\\'.$moduleName.'\\Service';
        if (! is_dir($directoryService)) {
            mkdir($directoryService, 0755, true);
        }
        if (! is_dir($directoryService."\\Impl")) {
            mkdir($directoryService."\\Impl", 0755, true);
        }

        $serviceFile = fopen($directoryService.'\\'.$nameService.'Service'.'.php',"w");
        $serviceText = '<?php
namespace Modules\\'.$moduleNameUpcase.'\Service;

use Core\Service\IBaseService;
use Modules\\'.$moduleNameUpcase.'\Model\\'.$modelName.';

/**
 * @extends IBaseService<'.$modelName.'>
 */
interface '.$nameService.'Service extends IBaseService {
}';
        fwrite($serviceFile, $serviceText);
        fclose($serviceFile);

        $serviceFileImpl = fopen($directoryService.'\Impl\\'.$nameService.'ServiceImpl'.'.php',"w");
        $serviceTextImpl = '<?php
namespace Modules\\'.$moduleNameUpcase.'\Service\Impl;

use Illuminate\Support\Facades\Auth;

use Modules\\'.$moduleNameUpcase.'\Service\\'.$nameService.'Service;
use Modules\\'.$moduleNameUpcase.'\Repository\\'.$nameService.'Repository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;


class '.$nameService.'ServiceImpl extends BaseService implements '.$nameService.'Service
{
    /** @var '.$nameService.'Repository */
    protected $baseRepo;

    public function __construct('.$nameService.'Repository $baseRepo) {
        parent::__construct($baseRepo);
    }

}';
        fwrite($serviceFileImpl, $serviceTextImpl);
        fclose($serviceFileImpl);


    // tạo file Filter
    $directoryFilter = 'Modules\\'.$moduleName.'\\Object';
    $filterFile = $directoryFilter.'\\Filter'.$nameService.'.php';
    $this->helperCreateFile($directoryFilter, $filterFile, '<?php
namespace Modules\\'.$moduleNameUpcase.'\Object;
use Core\Object\BaseObject;
/**
 *
 */
class Filter'.$nameService.' extends BaseObject {
    public function __construct($input = []) {
        parent::__construct($input);
    }
    public function buildConditions() {
        $conditions = [];
        return $conditions;
    }
}'
    );

    if($createRequest){
        $directory = 'Modules\\'.$moduleName.'\\Request';
        $searchFile = $directory.'\\FrmSearch'.$nameService.'Request.php';
        $this->helperCreateFile($directory, $searchFile, '<?php
namespace Modules\\'.$moduleNameUpcase.'\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\\'.$moduleNameUpcase.'\\Object\\Filter'.$nameService.';

class FrmSearch'.$nameService.'Request extends FormRequest
{
    protected $casts = [
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
        ];
    }

    public function messages() {
        return [
        ];
    }

    protected function prepareForValidation(): void
    {
        $normalized = [];
        foreach ($this->casts as $field => $type) {
            $normalized[$field] = core_normalize_type_value($type, $this->input($field));
        }
        if (!empty($normalized)) {
            $this->merge($normalized);
        }
    }

    /**
     * Chuyển đổi dữ liệu đầu vào thành đối tượng Filter'.$nameService.'.
     *
     * @return Filter'.$nameService.'
     */
    public function toFilter() : Filter'.$nameService.' {
        $validated = $this->validated();
        $filter = new Filter'.$nameService.'();
        foreach ($this->casts as $key => $type) {
            if (!array_key_exists($key, $validated)) {
                continue;
            }
            settype($validated[$key], $type);
            $filter->{$key} = $validated[$key];
        }
        return $filter;
    }
}
'
        );

        $storeFile = $directory.'\\FrmStore'.$nameService.'Request.php';
        $this->helperCreateFile($directory, $storeFile, '<?php
namespace Modules\\'.$moduleNameUpcase.'\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmStore'.$nameService.'Request extends FormRequest

{
    protected $casts = [
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages() {
        return [
        ];
    }

    protected function prepareForValidation(): void
    {
        // Một số cột trong DB có thể là số nhưng phía client gửi lên (hoặc server trả về) dạng number,
        // trong khi rule đang yêu cầu string -> cần chuẩn hoá number/bool thành string.
        $normalized = [];
         foreach ($this->casts as $field => $type) {
            if (!$this->has($field)) {
                continue;
            }
            $normalized[$field] = core_normalize_type_value($type, $this->input($field));
        }
        if (!empty($normalized)) {
            $this->merge($normalized);
        }
    }

    /**
     * Dữ liệu an toàn để ghi DB (chỉ field đã rule).
     *
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return $this->validated();
    }
}'
        );
    }

    if($createController){
        $directoryController = 'Modules\\'.$moduleName.'\\Controller';
        $controllerFile = $directoryController.'\\'.$nameService.'Controller.php';
        $this->helperCreateFile($directoryController, $controllerFile, '<?php

namespace Modules\\'.$moduleNameUpcase.'\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\\'.$moduleNameUpcase.'\Request\FrmSearch'.$nameService.'Request;
use Modules\\'.$moduleNameUpcase.'\Request\FrmStore'.$nameService.'Request;
use Modules\\'.$moduleNameUpcase.'\Service\\'.$nameService.'Service;

class '.$nameService.'Controller extends Controller {

    public function viewManage'.$nameService.'(Request $request): View {
        return view("'.$moduleName.'::viewManage'.$nameService.'");
    }

    public function viewStore'.$nameService.'(Request $request, ?int $id = null): View {
        /** @var '.$nameService.'Service $'.$nameService.'Service */
        $'.$nameService.'Service = app('.$nameService.'Service::class);
        $'.$nameService.' = $id ? $'.$nameService.'Service->findOne("no-cache",["id" => $id]) : null;
        return view("'.$moduleName.'::viewStore'.$nameService.'", [
            "'.$nameService.'" => $'.$nameService.',
        ]);
    }

    public function getPaginate(FrmSearch'.$nameService.'Request $request, string $page = "page-1"): JsonResponse {
        /** @var '.$nameService.'Service $'.$nameService.'Service */
        $'.$nameService.'Service = app('.$nameService.'Service::class);
        try {
            $filter = $request->toFilter();
            $result = $'.$nameService.'Service->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function getList(FrmSearch'.$nameService.'Request $request): JsonResponse {
        /** @var '.$nameService.'Service $'.$nameService.'Service */
        $'.$nameService.'Service = app('.$nameService.'Service::class);
        try {
            $filter = $request->toFilter();
            $result = $'.$nameService.'Service->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStore'.$nameService.'Request $request): JsonResponse {
        /** @var '.$nameService.'Service $'.$nameService.'Service */
        $'.$nameService.'Service = app('.$nameService.'Service::class);
        try {
            $data = $request->validated();
            $result = $'.$nameService.'Service->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var '.$nameService.'Service $'.$nameService.'Service */
        $'.$nameService.'Service = app('.$nameService.'Service::class);
        try {
            $result = $'.$nameService.'Service->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }
}
        ');
    }

    if($createView){
        $directoryView = 'Modules\\'.$moduleName.'\\View';
        $viewFile = $directoryView.'\\viewManage'.$nameService.'.blade.php';
        $this->helperCreateFile($directoryView, $viewFile, "@extends('page::layout')

@section('content')
    <div id='root-manage-'></div>
@endsection

@push('scripts')
@vite('')
@endpush
");

        $viewFile = $directoryView.'\\viewStore'.$nameService.'.blade.php';
        $this->helperCreateFile($directoryView, $viewFile, "@extends('page::layout')
@php
    \$pageProps = [
        ".$nameService." => \$'.$nameService.',
    ];
@endphp

@section('content')
    <div id='root-store-' data-props='{{ json_encode(\$pageProps) }}'></div>
@endsection

@push('scripts')
    @vite('')
@endpush");

    }

    $this->info($directoryModel.'\\'.$modelName.' '.$directoryRepository.'\\'.$nameService.' '.$directoryService.'\\'.$nameService.' ');


    }

    public function helperCreateFile(string $directory, string $fileName, string $fileText): void {
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $file = fopen($fileName,"w");
        fwrite($file, $fileText);
        fclose($file);
    }

}
