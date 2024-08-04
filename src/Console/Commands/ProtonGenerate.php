<?php

namespace App\Console\Commands;

use App\Codeton\GenerateMenuSidebar;
use App\Helpers\Utility;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ProtonGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:proton-generate';
    protected $signature = 'proton:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected $folderController = '';
    protected $folderView = '';

    protected function configure()
    {
        $this
            ->setName('proton:generate')
            ->setDescription('Generate skeleton')
            ->addArgument('folder', InputArgument::REQUIRED, 'The folder name')
            ->addArgument('file', InputArgument::REQUIRED, 'The file name')
            ->addOption('modal', null, InputOption::VALUE_NONE, 'Generate Form')
            ->addOption('menu', null, InputOption::VALUE_NONE, 'Generate menu as well')
            ;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $folder = $this->argument('folder');
        $this->folderController = $folder;
        $file = $this->argument('file');
        $generateMenu = $this->option('menu');

        // Lakukan pengolahan sesuai dengan argumen dan opsi
        $this->createController($folder, $file,$this->option('modal'));
        $this->createListViewClass($folder, $file,$this->option('modal'));
        $this->generateView($folder, $file, $this->option('modal'));
        $this->createRoutingController($folder, $file);
        
        if ($generateMenu) {
            $this->insertMenu($folder, $file);
        }

        $this->info('Files generated successfully.');
    }

    protected function createController($folder, $file,$viewType)
    {
        
        $folderPath = app_path('Http/Controllers/' . $folder.'/'.$file);

        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        
        $namespace = 'App\\Http\\Controllers\\' . str_replace('/', '\\', $folder).'\\'.str_replace('/', '\\', $file);
        $controllerName = "{$folderPath}/{$file}Controller.php";

        if (File::exists($controllerName)) {
            // Tampilkan konfirmasi
            if ($this->confirm("File {$controllerName} already exists. Do you want to replace it?")) {
                // Jika user menyetujui, hapus file yang ada
                File::delete($controllerName);
            } else {
                $this->info("Controller generation aborted.");

                return;
            }
        }
        if ($viewType) {
            $templateController = 'ControllerTemplateModal';
        }
        else{
            $templateController = 'ControllerTemplate';
        }
        // isi controller dari template
        $controllerContent = "<?php\n\n" . view('proton.controllers.'.$templateController.'', [
            'folder' => $namespace,
            'file' => $file,
            'folderName' => $this->folderController,
            'controllerPath' => Utility::toCamelCase($file)
            // 'imports' => $imports, // Jangan lupa menyertakan imports jika digunakan
        ])->render();

        // Simpan isi controller ke file
        file_put_contents($controllerName, $controllerContent);

        $this->info("Success generate controller {$controllerName}");
    }

    protected function createListViewClass($folder, $file,$viewType)
    {
        $folderPath = app_path('Http/Controllers/' . $folder.'/'.$file);

        // Pastikan folder sudah ada, jika belum, buat folder
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        $namespace = 'App\\Http\\Controllers\\' . str_replace('/', '\\', $folder).'\\'.str_replace('/', '\\', $file);
        $listView = "{$folderPath}/{$file}ListView.php";

        if (File::exists($listView)) {
            // Tampilkan konfirmasi
            if ($this->confirm("File {$listView} already exists. Do you want to replace it?")) {
                // Jika user menyetujui, hapus file yang ada
                File::delete($listView);
            } else {
                $this->info("List View Class generation aborted.");

                return;
            }
        }
        if ($viewType) {
            $templateListView = 'ListViewTemplateModal';
        }
        else{
            $templateListView = 'ListViewTemplate';
        }
        // Simpan isi controller dari template
        $controllerContent = "<?php\n\n" . view('proton.controllers.'.$templateListView.'', [
            'folder' => $namespace,
            'file' => $file,
            'folderName' => $this->folderController,
            'controllerPath' => Utility::toCamelCase($file)
            // 'imports' => $imports, // Jangan lupa menyertakan imports jika digunakan
        ])->render();


        // Simpan isi controller ke file
        file_put_contents($listView, $controllerContent);

        $this->info("Success generate controller {$listView}");
    }

    protected function generateView($folderPath, $file, $viewType)
    {
        $folderPath = 'resources/views/' . $folderPath;
        if (!is_dir($folderPath . '/' . $file)) {
            // Jika tidak valid, mungkin Anda perlu membuatnya terlebih dahulu
            mkdir($folderPath . '/' . $file, 0755, true);
        }
        $this->createPageIndex($folderPath, $file);
        if ($viewType) {
            $this->createFormModal($folderPath, $file);
        } else {
            $this->createFormPage($folderPath, $file);
        }
    }

    protected function createPageIndex($folderPath, $file)
    {
        $templatePath = resource_path("views/proton/views/PageIndexViewTemplate.blade.php");
        $viewContent = file_get_contents($templatePath);
        // $folderPath = 'resources/views/' . $folderPath;
        if (!is_dir($folderPath . '/' . $file)) {
            mkdir($folderPath . '/' . $file, 0755, true);
        }
        $newFile = "{$folderPath}/{$file}/page-index.blade.php";
        if (File::exists($newFile)) {
            // Tampilkan konfirmasi
            if ($this->confirm("View {$newFile} already exists. Do you want to replace it?")) {
                // Jika user menyetujui, hapus file yang ada
                File::delete($newFile);
            } else {
                // Jika user tidak menyetujui, keluar dari proses
                $this->info("Form generation aborted.");

                return;
            }
        }

        file_put_contents($newFile, $viewContent);
        $this->info("Generate {$newFile}");

    }
    protected function createFormModal($folderPath, $file)
    {
        $templatePath = resource_path("views/proton/views/FormModalViewTemplate.blade.php");
        $viewContent = file_get_contents($templatePath);
        $newFile = "{$folderPath}/{$file}/form.blade.php";
        if (File::exists($newFile)) {
            // Tampilkan konfirmasi
            if ($this->confirm("Form {$newFile} already exists. Do you want to replace it?")) {
                // Jika user menyetujui, hapus file yang ada
                File::delete($newFile);
            } else {
                // Jika user tidak menyetujui, keluar dari proses
                $this->info("Form generation aborted.");

                return;
            }
        }

        file_put_contents($newFile, $viewContent);
        $this->info("Generate {$newFile}");
    }

    protected function createFormPage($folderPath, $file)
    {
        $templatePath = resource_path("views/proton/views/FormPageViewTemplate.blade.php");
        $viewContent = file_get_contents($templatePath);
        if (!is_dir($folderPath . '/' . $file)) {
            mkdir($folderPath . '/' . $file, 0755, true);
        }
        $newFile = "{$folderPath}/{$file}/form.blade.php";
        if (File::exists($newFile)) {
            // Tampilkan konfirmasi
            if ($this->confirm("Form {$newFile} already exists. Do you want to replace it?")) {
                // Jika user menyetujui, hapus file yang ada
                File::delete($newFile);
            } else {
                // Jika user tidak menyetujui, keluar dari proses
                $this->info("Form generation aborted.");

                return;
            }
        }

        file_put_contents($newFile, $viewContent);
        $this->info("Generate {$newFile}");
    }

    protected function createRoutingController($folder, $file)
    {
        // Dapatkan path lengkap menuju folder controller
        $folderPath = ('routes/' . $folder);

        // Pastikan folder sudah ada, jika belum, buat folder
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        // Dapatkan namespace dari folder
        $namespace = 'App\\Http\\Controllers\\' . str_replace('/', '\\', $folder).'\\'.str_replace('/', '\\', $file);
        $routeFile = "{$folderPath}/Route_{$file}.php";

        if (File::exists($routeFile)) {
            // Tampilkan konfirmasi
            if ($this->confirm("Route Group {$routeFile} already exists. Do you want to replace it?")) {
                // Jika user menyetujui, hapus file yang ada
                File::delete($routeFile);
            } else {
                // Jika user tidak menyetujui, keluar dari proses
                $this->info("Route generation aborted.");

                return;
            }
        }
        $routeName = Utility::toCamelCase($file);
        // Buat isi controller dari template
        $controllerContent = "<?php\n\n" . view('proton.RouteTemplate', [
            'folder' => $namespace,
            'file' => $file.'Controller',
            'folderName' => $this->folderController,
            'controllerPath' => $routeName
            // 'imports' => $imports, // Jangan lupa menyertakan imports jika digunakan
        ])->render();

        // Tentukan path dan nama file controller


        // Tulis isi controller ke file
        file_put_contents($routeFile, $controllerContent);
        $this->addRouteWeb($routeName,$folder.'/Route_'.$file.'.php');
        $this->info("Success generate route {$routeFile}");
    }

    protected function insertMenu($folder,$file)
    {
        $newMenu = Utility::toCamelCase($file);
        DB::table('skeleton_setting_menu_access')->where('url',$newMenu)->delete();
        $dataInsert = [
            'id_parent' => 0,
            'menu_order' => DB::table('skeleton_setting_menu_access')->max('menu_order') + 1,
            'name' => $this->folderController .'/'. $file,
            'type' => 1,
            'url' => $newMenu,
            'icon' => 'far fa-square',
            'sess_name' => $newMenu,
            'access' => 1
        ];
        DB::table('skeleton_setting_menu_access')->insert($dataInsert);
        $menu = new GenerateMenuSidebar();
        session([
            'menu' => $menu->get_menu_access(1) //id_access
        ]);
        $this->info("Success create menu  " . $file . " :  {url($newMenu)}");
    }

    protected function addRouteWeb($uri, $fileLocation)
    {
        // Baca isi file
        $filePath = base_path('routes/web.php');
        $contents = file_get_contents($filePath);

        // Periksa apakah rute sudah ada sebelumnya
        if (strpos($contents, $fileLocation) === false) {
            // Tambahkan rute ke file
            file_put_contents($filePath, "\nRoute::group(['prefix' => '{$uri}'], __DIR__ . '/{$fileLocation}');\n", FILE_APPEND);
        } else {
            // Pesan jika rute sudah ada
            $this->info('Route already exists.');
        }
    }

}
