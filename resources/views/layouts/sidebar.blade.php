<style type="text/css">
  .u-vmenu > ul{
    padding-left: 0px;
    margin-bottom: 0px;
  }
  
</style>
<nav id="sidebar" class="border-right mt-5 py-5 mt-md-2 pt-md-2 mt-lg-0">
  <div class="sidebar-sticky">
    <h5 class="text-center text-success my-sm-5 my-lg-2 d-none d-md-block" style="letter-spacing: 2px;"><span class=" text-white p-2 h2">{{env('APP_NAME')}}</span></h5>
    <div class="u-vmenu mt-lg-2 p-2 pb-5 pt-lg-4">
      <li class="nav-item">
          <a class="nav-link" href="{{url('home')}}" data-active="home" data-option="off">
            <i class="fa fa-th-large"></i> &nbsp;Dashboard</a>
      </li>
       <?php
       echo session('menu');
       ?>
       
      </div>
      
  </div>
  <div class="sidebar-item d-none d-lg-block text-center text-white pt-1" style="height:250px;width:100%;background-color: rgba(255, 255, 255, 0.32);">
        <img src="{{asset('img/logo-sidebar.webp')}}" style="max-width: 50px;margin-top:5px;" alt="Midas APP" loading="lazy">
        <span class="ml-2 mt-5">Codage</span>
      </div>
</nav>
<div class="sidebar-backdrop" data-toggle="sidebar"></div>

