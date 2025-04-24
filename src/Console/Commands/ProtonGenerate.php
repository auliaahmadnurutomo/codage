<?php

namespace App\Console\Commands;

use App\Codeton\GenerateMenuSidebar;
use App\Helpers\Utility;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class ProtonGenerate
 * 
 * Generates scaffolding for new controllers, views, and routes
 */
class ProtonGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proton:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate application skeleton code';
    
    /**
     * Controller folder path.
     *
     * @var string
     */
    protected string $folderController = '';
    
    /**
     * View folder path.
     *
     * @var string
     */
    protected string $folderView = '';

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName('proton:generate')
            ->setDescription('Generate skeleton code')
            ->addArgument('folder', InputArgument::REQUIRED, 'The folder name')
            ->addArgument('file', InputArgument::REQUIRED, 'The file name')
            ->addOption('modal', null, InputOption::VALUE_NONE, 'Generate Form as modal')
            ->addOption('menu', null, InputOption::VALUE_NONE, 'Generate menu entry');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $folder = $this->argument('folder');
        $this->folderController = $folder;
        $file = $this->argument('file');
        $generateMenu = $this->option('menu');
        $useModalForm = $this->option('modal');

        // Process according to arguments and options
        $this->createController($folder, $file, $useModalForm);
        $this->createListViewClass($folder, $file, $useModalForm);
        $this->generateView($folder, $file, $useModalForm);
        $this->createRoutingController($folder, $file);

        if ($generateMenu) {
            $this->insertMenu($folder, $file);
        }

        $this->info('Files generated successfully.');
        
        return 0;
    }

    /**
     * Create controller file.
     *
     * @param string $folder The folder name
     * @param string $file The file name
     * @param bool $useModalForm Whether to use modal form
     * @return void
     */
    protected function createController(string $folder, string $file, bool $useModalForm): void
    {
        $folderPath = app_path("Http/Controllers/{$folder}/{$file}");

        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        $namespace = "App\\Http\\Controllers\\" . str_replace('/', '\\', $folder) . "\\" . str_replace('/', '\\', $file);
        $controllerName = "{$folderPath}/{$file}Controller.php";

        if (File::exists($controllerName)) {
            if (!$this->confirm("File {$controllerName} already exists. Do you want to replace it?")) {
                $this->info("Controller generation aborted.");
                return;
            }
            
            File::delete($controllerName);
        }
        
        $templateController = $useModalForm ? 'ControllerTemplateModal' : 'ControllerTemplate';
        
        // Generate controller content from template
        $controllerContent = "<?php\n\n" . view("proton.controllers.{$templateController}", [
            'folder' => $namespace,
            'file' => $file,
            'folderName' => $this->folderController,
            'controllerPath' => Utility::toCamelCase($file)
        ])->render();

        // Save controller content to file
        file_put_contents($controllerName, $controllerContent);

        $this->info("Successfully generated controller {$controllerName}");
    }

    /**
     * Create ListView class file.
     *
     * @param string $folder The folder name
     * @param string $file The file name
     * @param bool $useModalForm Whether to use modal form
     * @return void
     */
    protected function createListViewClass(string $folder, string $file, bool $useModalForm): void
    {
        $folderPath = app_path("Http/Controllers/{$folder}/{$file}");

        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        $namespace = "App\\Http\\Controllers\\" . str_replace('/', '\\', $folder) . "\\" . str_replace('/', '\\', $file);
        $listView = "{$folderPath}/{$file}ListView.php";

        if (File::exists($listView)) {
            if (!$this->confirm("File {$listView} already exists. Do you want to replace it?")) {
                $this->info("List View Class generation aborted.");
                return;
            }
            
            File::delete($listView);
        }
        
        $templateListView = $useModalForm ? 'ListViewTemplateModal' : 'ListViewTemplate';
        
        // Generate ListView content from template
        $listViewContent = "<?php\n\n" . view("proton.controllers.{$templateListView}", [
            'folder' => $namespace,
            'file' => $file,
            'folderName' => $this->folderController,
            'controllerPath' => Utility::toCamelCase($file)
        ])->render();

        // Save ListView content to file
        file_put_contents($listView, $listViewContent);

        $this->info("Successfully generated ListView {$listView}");
    }

    /**
     * Generate view files.
     *
     * @param string $folder The folder name
     * @param string $file The file name
     * @param bool $useModalForm Whether to use modal form
     * @return void
     */
    protected function generateView(string $folder, string $file, bool $useModalForm): void
    {
        $folderPath = "resources/views/{$folder}";
        
        if (!is_dir("{$folderPath}/{$file}")) {
            mkdir("{$folderPath}/{$file}", 0755, true);
        }
        
        $this->createPageIndex($folderPath, $file);
        
        if ($useModalForm) {
            $this->createFormModal($folderPath, $file);
        } else {
            $this->createFormPage($folderPath, $file);
        }
    }

    /**
     * Create page index view.
     *
     * @param string $folderPath The folder path
     * @param string $file The file name
     * @return void
     */
    protected function createPageIndex(string $folderPath, string $file): void
    {
        $templatePath = resource_path("views/proton/views/PageIndexViewTemplate.blade.php");
        $viewContent = file_get_contents($templatePath);
        
        if (!is_dir("{$folderPath}/{$file}")) {
            mkdir("{$folderPath}/{$file}", 0755, true);
        }
        
        $newFile = "{$folderPath}/{$file}/page-index.blade.php";
        
        if (File::exists($newFile)) {
            if (!$this->confirm("View {$newFile} already exists. Do you want to replace it?")) {
                $this->info("Page index generation aborted.");
                return;
            }
            
            File::delete($newFile);
        }

        file_put_contents($newFile, $viewContent);
        $this->info("Generated {$newFile}");
    }

    /**
     * Create modal form view.
     *
     * @param string $folderPath The folder path
     * @param string $file The file name
     * @return void
     */
    protected function createFormModal(string $folderPath, string $file): void
    {
        $templatePath = resource_path("views/proton/views/FormModalViewTemplate.blade.php");
        $viewContent = file_get_contents($templatePath);
        $newFile = "{$folderPath}/{$file}/form.blade.php";
        
        if (File::exists($newFile)) {
            if (!$this->confirm("Form {$newFile} already exists. Do you want to replace it?")) {
                $this->info("Form generation aborted.");
                return;
            }
            
            File::delete($newFile);
        }

        file_put_contents($newFile, $viewContent);
        $this->info("Generated {$newFile}");
    }

    /**
     * Create page form view.
     *
     * @param string $folderPath The folder path
     * @param string $file The file name
     * @return void
     */
    protected function createFormPage(string $folderPath, string $file): void
    {
        $templatePath = resource_path("views/proton/views/FormPageViewTemplate.blade.php");
        $viewContent = file_get_contents($templatePath);
        
        if (!is_dir("{$folderPath}/{$file}")) {
            mkdir("{$folderPath}/{$file}", 0755, true);
        }
        
        $newFile = "{$folderPath}/{$file}/form.blade.php";
        
        if (File::exists($newFile)) {
            if (!$this->confirm("Form {$newFile} already exists. Do you want to replace it?")) {
                $this->info("Form generation aborted.");
                return;
            }
            
            File::delete($newFile);
        }

        file_put_contents($newFile, $viewContent);
        $this->info("Generated {$newFile}");
    }

    /**
     * Create routing controller file.
     *
     * @param string $folder The folder name
     * @param string $file The file name
     * @return void
     */
    protected function createRoutingController(string $folder, string $file): void
    {
        $folderPath = "routes/{$folder}";

        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        $namespace = "App\\Http\\Controllers\\" . str_replace('/', '\\', $folder) . "\\" . str_replace('/', '\\', $file);
        $routeFile = "{$folderPath}/Route_{$file}.php";

        if (File::exists($routeFile)) {
            if (!$this->confirm("Route Group {$routeFile} already exists. Do you want to replace it?")) {
                $this->info("Route generation aborted.");
                return;
            }
            
            File::delete($routeFile);
        }
        
        $routeName = Utility::toCamelCase($file);
        
        // Generate route content from template
        $routeContent = "<?php\n\n" . view('proton.RouteTemplate', [
            'folder' => $namespace,
            'file' => "{$file}Controller",
            'folderName' => $this->folderController,
            'controllerPath' => $routeName
        ])->render();

        file_put_contents($routeFile, $routeContent);
        $this->addRouteWeb($routeName, "{$folder}/Route_{$file}.php");
        $this->info("Successfully generated route {$routeFile}");
    }

    /**
     * Insert menu entry in database.
     *
     * @param string $folder The folder name
     * @param string $file The file name
     * @return void
     */
    protected function insertMenu(string $folder, string $file): void
    {
        $newMenu = Utility::toCamelCase($file);
        DB::table('skeleton_setting_menu_access')->where('url', $newMenu)->delete();
        
        $dataInsert = [
            'id_parent' => 0,
            'menu_order' => DB::table('skeleton_setting_menu_access')->max('menu_order') + 1,
            'name' => "{$this->folderController}/{$file}",
            'type' => 1,
            'url' => $newMenu,
            'icon' => 'far fa-square',
            'sess_name' => $newMenu,
            'access' => 1,
            'uuid' => Str::uuid(),
        ];
        
        DB::table('skeleton_setting_menu_access')->insert($dataInsert);
        $menuAccess = DB::table('skeleton_setting_menu_access')->orderBy('menu_order')->get();
        $menu = new GenerateMenuSidebar();
        
        session([
            'menu' => $menu->getMenuAccess($menuAccess)
        ]);
        
        $this->info("Successfully created menu {$file}: url({$newMenu})");
    }

    /**
     * Add route entry to web.php.
     *
     * @param string $uri URI for the route
     * @param string $fileLocation File location of the route
     * @return void
     */
    protected function addRouteWeb(string $uri, string $fileLocation): void
    {
        $filePath = base_path('routes/web.php');
        $contents = file_get_contents($filePath);

        if (strpos($contents, $fileLocation) === false) {
            file_put_contents(
                $filePath, 
                "\nRoute::group(['prefix' => '{$uri}'], __DIR__ . '/{$fileLocation}');\n",
                FILE_APPEND
            );
        } else {
            $this->info('Route already exists.');
        }
    }
}
