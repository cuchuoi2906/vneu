<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
			<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

		<!--@title@-->
		<!--@description@-->
		<!--@keywords@-->
		<!--@canonical@-->

		<!-- Google font -->
		<link href="https://fonts.googleapis.com/css?family=Lato:700%7CMontserrat:400,600" rel="stylesheet">

		<!-- Bootstrap -->
		<link type="text/css" rel="stylesheet" href="<?php echo BASE_URL_FOR_PUBLIC; ?>css/bootstrap.min.css"/>

		<!-- Font Awesome Icon -->
		<link rel="stylesheet" href="<?php echo BASE_URL_FOR_PUBLIC; ?>css/font-awesome.min.css">

		<!-- Custom stlylesheet -->
		<link type="text/css" rel="stylesheet" href="<?php echo BASE_URL_FOR_PUBLIC; ?>css/common.css?1"/>
		<link href="<?php echo BASE_URL_FOR_PUBLIC; ?>css/24hplayer.min.css?v=2024012620030" rel="stylesheet" type="text/css">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
        <style>
            .dropdown:hover .dropdown-menu {
                display: block;
            }
            .btn-language {
	            top:12px;
	        }
	        .btn-language img {
	            transition: transform 0.3s ease;
	        }

	        .btn-group:hover .dropdown-menu {
	            display: block;
	        }

	        .dropdown-menu {
	            display: none;
	        }
	    </style>
		<!-- Google tag (gtag.js) -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-11XCHNJ43V"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'G-11XCHNJ43V');
		</script>
	</head>
	<body>
		<!-- Header -->
		<header id="header" class="navbar navbar-default navbar-fixed-top">
			<div class="container">

				<div class="navbar-header">
					<!-- Logo -->
					<div class="navbar-brand">
						<a class="logo" href="<?php echo BASE_URL_FOR_PUBLIC; ?>">
                            <img src="<?php echo html_image(IMAGE_NEWS.'/images/vneu.png',false); ?>" alt="logo">
						</a>
					</div>
					<!-- /Logo -->

					<!-- Mobile toggle -->
					<button class="navbar-toggle">
						<span></span>
					</button>
					<!-- /Mobile toggle -->
				</div>

				<!-- Navigation -->
				<nav id="nav">
					<ul class="main-menu nav navbar-nav navbar-right">
                    <?php 
					$rs_cate = fe_danh_sach_chuyen_muc();
					$rs_cate_cap2 = [];
					$rs_cate_cap1 = [];
					if(check_array($rs_cate)){
						foreach($rs_cate as $items){
							if(intval($items['Parent']) == 0){
								$rs_cate_cap1[] = $items;
								continue;
							}
							$rs_cate_cap2[$items['Parent']][] = $items;
						}
					}
					if(check_array($rs_cate_cap1)){
						foreach($rs_cate_cap1 as $items){
							$name = $items['Name'];
                            $v_url_cate = get_url_origin_of_category($items);
							$classToggle = '';
							if(isset($rs_cate_cap2[$items['ID']]) && check_array($rs_cate_cap2)){
								$classToggle = 'dropdown-toggle';
							}
						?>
                            <li class="dropdown">
                                <a class="nav-link <?php echo $classToggle; ?> text-uppercase" href="<?php echo $v_url_cate; ?>" id="navbarDropdown">
                                    <?php echo $name; 
                                    if(isset($rs_cate_cap2[$items['ID']]) && check_array($rs_cate_cap2)){
                                    ?>
                                        <i class="fa fa-caret-down"></i>
                                    <?php 
                                    }?>
                                </a>
                                <?php 
								if(isset($rs_cate_cap2[$items['ID']]) && check_array($rs_cate_cap2)){
								?>
                                    <ul class="dropdown-menu">
										<?php 
										foreach($rs_cate_cap2[$items['ID']] as $cap2){
											$name = $cap2['Name'];
                            				$v_url_cate2 = get_url_origin_of_category($cap2);
										?>
                                            <li>
                                                <a href="<?php echo $v_url_cate2; ?>" class="text-uppercase">
                                                <?php echo $name; ?>
                                                </a>
                                            </li>
										<?php 
										}?>
									</ul>
								<?php 
								}?>
                            </li>
                        <?php 
                        }
                    }?>
					</ul>
				</nav>
				<!-- /Navigation -->
				<div class="btn-group">
			        <button type="button" class="btn btn-default btn-language">
			        	<img src="<?php echo html_image(IMAGE_NEWS.'/images/internet.png',false); ?>" alt="">
			        </button>
			        <ul class="dropdown-menu">
			            <li><a href="?lang=vi"><img src="<?php echo html_image(IMAGE_NEWS.'/images/vietnam.png',false); ?>" alt="Vietnamese"> Vi</a></li>
			            <li><a href="?lang=de"><img src="<?php echo html_image(IMAGE_NEWS.'/images/germany.png',false); ?>" alt="German"> De</a></li>
			        </ul>
			    </div>

			</div>
		</header>
		<!-- /Header -->