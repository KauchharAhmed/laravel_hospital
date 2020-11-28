<!DOCTYPE html>
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title>Login</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #1 for " name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="{{URL::to('public/assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{URL::to('public/assets/global/plugins/simple-line-icons/simple-line-icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{URL::to('public/assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{URL::to('public/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="{{URL::to('public/assets/global/css/components-rounded.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{URL::to('public/assets/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="{{URL::to('public/assets/pages/css/lock.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" /> </head>
    <!-- END HEAD -->
    <body class="" style="background: url('images/login_image.jpg');background-size:cover;background-repeat: repeat-y;background-repeat: repeat-x;">
        <div class="page-lock">
            <div class="page-logo">
                <a class="brand" href="index.html"></a>
            </div>
            <div class="page-body" style="background-color: #d7dde4;">
                <div class="lock-head" style="background-color:#3F51B5; color: #f0f5f4 ;"> HOSPITAL MANAGEMENT</div>
                <div class="lock-body">
                    <div class="lock-cont">
                        <div class="lock-item">
                            <div class="pull-left lock-avatar-block">
                                <!--<img src="{{URL::to('public/assets/pages/media/profile/photo3.jpg')}}" class="lock-avatar">--> </div>
                        </div>
                        <div class="lock-item lock-item-full" style="padding-left: 40px">
                            @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                </ul>
                            </div>
                            @endif
                            @if (!empty(Session::get('login_faild')))
                            <div class="alert alert-danger">
                            <?php
$message = Session::get('login_faild');
if ($message) {
	echo $message;
	Session::put('login_faild', null);
}
?>
    @endif
   

                            @if (!empty(Session::get('hyperlink')))
                            <div class="alert alert-danger">
                                <center><span style="color:blue">OR</span></center><br/>
                                <center><a href="{{URL::to('adminLogout')}}">Click Here Then Try To Login</a></center>
                               <?php Session::put('hyperlink', null); ?>

                            </div>
                            @endif
                       
                            @if (!empty(Session::get('password_change')))
                            <div class="alert alert-info">
                            <?php
$message1 = Session::get('password_change');
if ($message1) {
	echo $message1;
	Session::put('password_change', null);
}
?>
                            </div>
                            @endif
                            {!! Form::open(['url' => 'login','method' => 'post' , 'class'=> 'lock-form pull-left' ]) !!}
                                <div class="form-group">
                                    <input class="form-control placeholder-no-fix" type="mobile" autocomplete="off" placeholder="Mobile" name="mobile"/ style=" background-color:#9ca5b3;border: 2px solid #3F51B5; color: yellow; font-weight: bold;" required="">
                                </div>
                                <div class="form-group">
                                    <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password"/ style=" background-color:#9ca5b3;border: 2px solid #3F51B5;color: yellow; font-weight: bold;" required="">
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn red uppercase" style="background-color:#4db39f;border-color:black; font-weight: bold;">Login</button>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <div class="lock-bottom" style="background-color:#3F51B5; color: #f0f5f4 ;">
                    <!--<a href="{{URL::to('mobileNumberVerify')}}">FORGOTTEN PASSWORD ? </a>--><!-- <a href="{{URL::to('images/PanjabiWorld.apk')}}">Download Apps</a>-->
                </div>
            </div>
            <div class="page-footer-custom"> <?php echo date("Y"); ?> &copy; Asian IT INC </div>
        </div>
        <!--[if lt IE 9]>
        <script src="assets/global/plugins/respond.min.js"></script>
        <script src="assets/global/plugins/excanvas.min.js"></script>
        <script src="assets/global/plugins/ie8.fix.min.js"></script>
        <![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="{{URL::to('public/assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/js.cookie.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/jquery.blockui.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="{{URL::to('public/assets/global/scripts/app.min.js')}}" type="text/javascript"></script>

        <script>
            $(document).ready(function()
            {
                $('#clickmewow').click(function()
                {
                    $('#radio1003').attr('checked', 'checked');
                });
            })
        </script>
    </body>

</html>