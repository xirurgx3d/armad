<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Req
 * Date: 15.12.11
 * Time: 13:04
 * To change this template use File | Settings | File Templates.
 */
if(!defined('BASEPATH')) exit('No direct script access allowed');
class frontend extends CI_Controller
{
    function _remap($method, $params = array())
    {

        if(Segment(1)=='test_top_menu'){
            $this->test_top_menu();
            return;
        }
	    //foreach($_SERVER as $k => $p)  echo '<script style="height: 0" type="text/javascript"> console.log("'.$k.' = '.$p.'"); </script>';

	    $url = $this->uri->uri_string();

//	    $url = '/'.implode(str_replace(array('(',')'),array('',''),$params),'/');
//	    if ($url == '/')
//	    {
//		    $url = $_SERVER['REQUEST_URI'];
//	    }
//	    else if($_SERVER['REQUEST_URI'][strlen($_SERVER['REQUEST_URI'])-1] == '/') $url .= '/';
//
//	    $pos = strpos($_SERVER['REQUEST_URI'],'?');
//	    if($pos)
//	    {
//		    $url = substr($_SERVER['REQUEST_URI'], 0, $pos);
//	    }

	    $tab = 0;
	    $page = 0;

//	    echo '<script style="height: 0" type="text/javascript"> console.log("---------"); </script>';
//		echo '<script style="height: 0" type="text/javascript"> console.log("Определённая страница '.$url.'"); </script>';
//		echo '<script style="height: 0" type="text/javascript"> console.log("Метод '.$method.'"); </script>';
//		echo '<script style="height: 0" type="text/javascript"> console.log("Запрос сервера '.$_SERVER['REQUEST_URI'].'"); </script>';
//		echo '<script style="height: 0" type="text/javascript"> console.log("Запрос по версии CI '.$this->uri->uri_string().'"); </script>';
//		echo '<script style="height: 0" type="text/javascript"> console.log("Запрос по параметрам remap '.implode(str_replace(array('(',')'),array('',''),$params),'/').'"); </script>';
//		foreach($_GET as $p) echo '<script style="height: 0" type="text/javascript"> console.log("Параметр get '.$p.'"); </script>';

	    if($method == '$1')
	    {
		    if($url[0] == '/') $url = substr($url, 1);
		    if($url[strlen($url)-1] == '/') $url = substr($url, 0, strlen($url)-1);

		    $page = $this->mdl_add_page->get('', array('page_url'=>$url));
		    if(empty($page))
		    {
			    $url .= '/';
			    $page = $this->mdl_add_page->get('', array('page_url'=>$url));
		    }

			if(empty($page))
			{
				header("HTTP/1.0 404 Not Found");
				header("Status: 404 Not Found");
				$url = 'error-404/';
				$page = $this->mdl_add_page->get('', array('page_url'=>$url));
			}
	    }

	    if($method == 'tabs')
	    {
		    if($url[0] == '/') $url = substr($url, 1);
		    if($url[strlen($url)-1] == '/') $url = substr($url, 0, strlen($url)-1);

		    $page = $this->mdl_add_page->get('', array('page_url'=>$url));
		    if(empty($page))
		    {
			    $url .= '/';
			    $page = $this->mdl_add_page->get('', array('page_url'=>$url));
		    }

			if(empty($page))
			{
				//echo '<script style="height: 0" type="text/javascript"> console.log("Страница не найдена :'.$url.'"); </script>';
				//echo '<script style="height: 0" type="text/javascript"> console.log("Это не страница"); </script>';

				$url = str_replace(array('(',')'),array('',''),$params[0]);
				$page = $this->mdl_add_page->get('', array('page_url'=>$url));
				if(empty($page))
				{
					$url .= '/';
					$page = $this->mdl_add_page->get('', array('page_url'=>$url));
				}

				//echo '<script style="height: 0" type="text/javascript"> console.log("Кол-во секций - '.count($params).'"); </script>';
				if(count($params) > 2)
				{
					$url = str_replace(array('(',')'),array('',''),$params[0]) . '/' . str_replace(array('(',')'),array('',''),$params[1]) . '/';
					$page = $this->mdl_add_page->get('', array('page_url'=>$url));
					$tab = str_replace(array('(',')'),array('',''),$params[2]);
				}
				else
				{
					$tab = str_replace(array('(',')'),array('',''),$params[1]);

				}

				if (($tab != 'complect')&&
				    ($tab != 'cars-for-sale' )&&
					($tab != 'configurator' )&&
					($tab != 'galery' )&&
				    ($tab != 'otzyvy-pressy' )&&
					($tab != 'aksessuary' ))
						{
							//echo '<script style="height: 0" type="text/javascript"> console.log("Текущий таб :'.$tab.' не совпадает ни с одним"); </script>';
							$tab = 0;
						}

				if((empty($page))||(! $tab))
				{
					header("HTTP/1.0 404 Not Found");
					header("Status: 404 Not Found");
					$url = 'error-404/';
					$page = $this->mdl_add_page->get('', array('page_url'=>$url));
					$method = '$1';
				}
			}
		    else
		    {
			    //echo '<script style="height: 0" type="text/javascript"> console.log("Страница найдена :'.$url.'"); </script>';
		        $method = '$1';
		    }
	    }

//	    echo '<script style="height: 0" type="text/javascript"> console.log("Страница - '.$url.'"); </script>';
//	    echo '<script style="height: 0" type="text/javascript"> console.log("Таб - '.$tab.'"); </script>';
//	    echo '<script style="height: 0" type="text/javascript"> console.log("Метод - '.$method.'"); </script>';

        if(isset($_GET['t'])){
            $this->tabs($_GET['t']);
        }
        else{

            switch($method){
                case '$1': $this->page($url, $page);break;
                case 'index': $this->index();break;
                case 'tradein': $this->tradein($_GET['p']);break;
                case 'trade-in': $this->page();break;
                case 'testdrive': $this->testdrive($_GET['p']);break;
                case 'body_repair': $this->body_repair($_GET['p']);break;
                case 'callclient_to': $this->callclient_to($_GET['p']);break;
                case 'assurante': $this->assurante($_GET['p']);break;
                case 'boyoutCar': $this->boyoutCar($_GET['p']);break;
                case 'credits': $this->credits($_GET['p']);break;
                case 'know_interest': $this->know_interest($_GET['p']);break;
                case 'bonus': $this->bonus($_GET['p']);break;
                case 'zapchasti': $this->zapchasti($_GET['p']);break;
                case 'carrequest': $this->carrequest($_GET['p']);break;
	            case 'print_accessories': $this->print_accessories($params[0]);break;
                case 'tabs': $this->tabs('', $url, $tab, $page);break;
	            case 'get_menu': $this->get_menu();break;
            }
        }
    }

    function frontend()
	{
		parent::__Construct();
	}

    function index()
    {
        $CI = &get_instance();
        /*
        $CI->load->model("mdl_add_page");
        if(isset($_GET['id'])){
            $data['id'] = $CI->mdl_add_page->get($_GET['id']);
        }*/
        
        if($logoin=='peta'){
            
        }

        $CI->load->model("mdl_add_page");
        $data['news'] = $CI->mdl_add_page->getlist(false, 4, 'info_date', 'desc', array("page_rubric"=>"новости", "status"=>"Опубликовано"));

        //Загрузка всех страниц кроме главной
        $query = $this->db->query("SELECT * FROM cm_page WHERE page_pattern!='main' && status='Опубликовано' ORDER BY page_number");
        $data['one'] = $query->result_array();
        //Загрузка модулей
        $CI->load->model("mdl_module");
        $data['module'] = $CI->mdl_module->getlist(false, false, 'modul_position', '', array("module_status"=>"Опубликовано"));
        $data['banners'] = $CI->mdl_module->getlist(false, false, 'modul_position', '', array("module_status"=>"Опубликовано", "modul_location"=> 2));

        //Загрузка ссылок компонента управление меню
        //загрузка ссылок
        $this->load->model("mdl_links");
        $data['links'] = $this->mdl_links->getlist('','','link_position');

        //загрузка содержимиого главной стрницы
        $data['content_main'] = $CI->mdl_add_page->get('', array('page_pattern'=>'main', 'status'=>'Опубликовано'));

	    if($data['content_main'])
	    {
	        $keywords = explode('|', $data['content_main']['keywords']);
		    $data['content_main']['keywords'] = $keywords[0];

		    $titles = explode('|', $data['content_main']['seo_title']);
		    $data['content_main']['seo_title'] = $titles[0];

		    $descriptions = explode('|', $data['content_main']['description']);
		    $data['content_main']['description'] = $descriptions[0];
	    }

	    //Загрузка базовых параметров
		$CI->load->model('mdl_basic_config');
		$basic_config = $CI->mdl_basic_config->getlist();
		$data['basic_config'] = $basic_config[0];

	    //загрузка меню
	    $CI->load->model('mdl_menu');
		$menus = $CI->mdl_menu->getlist();
	    function get_href($menu, $pages)
		{
			if (is_array($menu))
				foreach($menu as $k => $m)
				{
					foreach($pages as $p)
					{
						if($p['id'] == $m['href'])
						{
							$menu[$k]['id'] = $menu[$k]['href'];
							$menu[$k]['href'] = $p['page_url'];
							break;
						}
					}
					if(isset($m['sub_options'])) $menu[$k]['sub_options'] = get_href($m['sub_options'], $pages);
				}
			return $menu;
		}
	    foreach($menus as $i => $menu)
	    {
			$data['menus'][$i]['menu_content'] = json_decode($menu['menu_structure'], TRUE);
			unset($data['menus'][$i]['menu_content']['name']);
			$data['menus'][$i]['menu_name'] = $menu['menu_name'];
			$data['menus'][$i]['menu_id'] = $menu['id'];
		    $data['menus'][$i]['menu_content'] = get_href($data['menus'][$i]['menu_content'], $data['one']);
	    }

        //загрузка слайдов
        $CI->load->model("mdl_slide");
        //$data['slide_car'] = $CI->mdl_slide->getlist(false, false, 'slide_position', '', array("slide_status"=>1, 'slide_type'=>'slide_car'));
        //$data['slide_main'] = $CI->mdl_slide->getlist(false, false, 'slide_position', '', array("slide_status"=>1, 'slide_type'=>'slide_main'));
	    //$data['slide_all'] = array_merge($data['slide_main'], $data['slide_car']);
		//shuffle($data['slide_all']);

	    //запоминаем индексы для машин
//	    $car_pos = array();
//	    foreach($data['slide_all'] as $n => $s) if ($s['slide_type'] == 'slide_car') $car_pos[$s['id']] = $n;
//	    $data['car_pos'] = $car_pos;

	    //Загрузка head
	    $CI->load->model('mdl_basic_config');
		$basic_config = $CI->mdl_basic_config->getlist();

	    if(isset($basic_config[0])) if(isset($basic_config[0]['head_content'])) $data['head_content'] = str_replace('{{base_url}}', base_url(), $basic_config[0]['head_content']);

        //загрузка блоков страницы
        $data['head'] = $this->load->view("blocks/head", $data, true);
        $data['header_content'] = $this->load->view("blocks/header_content", $data, true);
        $data['baner_main'] = $this->load->view("blocks/baner-main", $data, true);
        $data['index_content'] = $this->load->view("blocks/index-content", $data, true);
        $data['footer'] = $this->load->view("blocks/footer-index", $data, true);
        $this->load->view("index",$data);
    }

    /**
     * Вывод страницы
     */
    function page($url = 0, $page = 0)
    {
        $data['activtab'] = 0;
        $content = 'right_content';

        //загрузка страницы
        if(! $url){
            $url = ($this->uri->segment(1) == 'trade-in') ? $this->uri->segment(1) : $this->uri->uri_string();
        }

		if(! $page){
			$this->load->model("mdl_add_page");
            $data['v'] = $this->mdl_add_page->get('', array('page_url'=>$url, 'status'=>'Опубликовано'));

            if(empty($data['v']))
            {
	            $data['v'] = $this->mdl_add_page->get('', array('page_url'=>'error-404/'));
            }
		}
	    else $data['v'] = $page;

	    //Разбивка составных сео-блоков
		    $keywords = explode('|', $data['v']['keywords']);
		    $data['v']['keywords'] = $keywords[0];

		    $titles = explode('|', $data['v']['seo_title']);
		    $data['v']['seo_title'] = $titles[0];

		    $descriptions = explode('|', $data['v']['description']);
		    $data['v']['description'] = $descriptions[0];

	    $data['v']['content'] = str_replace('{{base_url}}', base_url(), $data['v']['content']);

	    $data['content_main'] = $this->mdl_add_page->get('', array('page_pattern'=>'main', 'status'=>'Опубликовано'));

	    if($data['content_main'])
	    {
	        $keywords = explode('|', $data['content_main']['keywords']);
		    $data['content_main']['keywords'] = $keywords[0];

		    $titles = explode('|', $data['content_main']['seo_title']);
		    $data['content_main']['seo_title'] = $titles[0];

		    $descriptions = explode('|', $data['content_main']['description']);
		    $data['content_main']['description'] = $descriptions[0];
	    }

        $id = $data['v']['id'];
        $data['id'] = $id;

	    if($data['v']['page_pattern']=='car')
	    {
			$CI = &get_instance();
			$CI->load->model("mdl_photogalery");
			$galery_items = $CI->mdl_photogalery->getSelAll('id_page', $id);
			$data['galery_flash'] = $data['galery_exterior'] = $data['galery_interior'] = $data['galery_video'] = array();

			if(is_array($galery_items)) foreach($galery_items as $item)
			{
				switch($item['file_type'])
				{
					case 1 : $data['galery_flash'][] = $item; break;
					case 2 : $data['galery_exterior'][] = $item; break;
					case 3 : $data['galery_interior'][] = $item; break;
					case 4 : $data['galery_video'][] = $item;
				}
			}
	    }

        //Загрузка модуля
        $this->load->model("mdl_module");
        $data['module'] = $this->mdl_module->getlist(false, false, 'modul_position', '', array("module_status"=>"Опубликовано"));
        //Загрузка автомобилей в блок tradein-carlist
        $this->load->model("mdl_add_car");
        $data['cars'] = $this->mdl_add_car->getlist(false, false, '', '', array('public'=>'Опубликовано'));
	    //Загрузка списка тестдрайвов
        $this->load->model("mdl_add_testdrive");
        $data['testdrives'] = $this->mdl_add_testdrive->getlist(false, false, 'testdrive_position', '', array("switch"=>1));
        //Загрузка подробного описания автомобиля
        if(isset($_GET['car'])){
            $data['car'] = $this->mdl_add_car->get('', array('id'=>$_GET['car']));
            $data['car']['photos'] = explode(" | ", $data['car']['photos']);
            $data['car']['comfort'] = explode(" | ",$data['car']['comfort']);
            $content = 'tradein-carview';
	        $url = $this->uri->segment(1);
	        $data['v'] = $this->mdl_add_page->get('', array('page_url'=>$url.'/'));
        }
        elseif(isset($_GET['form'])){
            $data['car'] = $this->mdl_add_car->get($_GET['form']);
            $content = 'form_tradein';
	        $url = $this->uri->segment(1);
	        $data['v'] = $this->mdl_add_page->get('', array('page_url'=>$url.'/'));
        }
        //Загрузка параметров дополнительных работ формы ТО
        $this->load->model("mdl_formto");
        $data['works'] = $this->mdl_formto->getlist();
        //Загрузка заголовка формы
        $this->load->model("mdl_formtitle");
        $data['form_title'] = $this->mdl_formtitle->getSel("type_form","to");

        //Загрузка форм
        $data['body_repair'] = $this->load->view("blocks/form-body-repair", $data, true);
        $data['tradein_carlist'] = $this->load->view("blocks/tradein-carlist",$data,true);
        $data['form_TO'] = $this->load->view("blocks/form-TO", $data, true);
        $data['form_testdrive'] = $this->load->view("blocks/form-testdrive", $data, true);
        $data['zapchasti'] = $this->load->view("blocks/form_zapchasti", $data, true);
        $data['carrequest'] = $this->load->view("blocks/form_carrequest", $data, true);

        $data['assurance'] = $this->load->view("blocks/form_assurance", $data, true);
        $data['boyout_car'] = $this->load->view("blocks/form-boyout_car", $data, true);
        $data['credits'] = $this->load->view("blocks/form-credits", $data, true);

        //Загрузка модулей в Sidebar
        $this->load->model("mdl_rsidebar");
        $data['rsdbar'] = $this->mdl_rsidebar->getlist('', '', 'sdbar_position', '', array('sdbar_status'=>1));
        if(!empty($data['rsdbar'])){
            for($i=0; $i<count($data['rsdbar']); $i++){
                $data['rsdbar'][$i]['sdbar_pages'] = explode(" | ", $data['rsdbar'][$i]['sdbar_pages']);
            }
        }

	    //Загрузка списков страниц по рубрикам
	    $this->load->model("mdl_rubric");
        $rubrics = $this->mdl_rubric->getlist();

	    $CI = &get_instance();
	    $CI->load->model('mdl_add_page');
	    $pages = $CI->mdl_add_page->getlist(false, false, 'info_date', 'desc', array("status"=>'Опубликовано'));

	    foreach($rubrics as $rubric)
	    {
		    $data['rubrics'][$rubric['rubric_name']] = array();

		    foreach($pages as $page)
			    if(strstr( $page['page_rubric'], $rubric['rubric_name'] ))
		            $data['rubrics'][$rubric['rubric_name']][] = $page;
	    }

	    //загрузка меню
	    $CI->load->model('mdl_menu');
		$menus = $CI->mdl_menu->getlist();
	    function get_href($menu, $pages)
		{
			if (is_array($menu))
				foreach($menu as $k => $m)
				{
					foreach($pages as $p)
					{
						if($p['id'] == $m['href'])
						{
							$menu[$k]['id'] = $menu[$k]['href'];
							$menu[$k]['href'] = $p['page_url'];
							break;
						}
					}
					if(isset($m['sub_options'])) $menu[$k]['sub_options'] = get_href($m['sub_options'], $pages);
				}
			return $menu;
		}
	    foreach($menus as $i => $menu)
	    {
			$data['menus'][$i]['menu_content'] = json_decode($menu['menu_structure'], TRUE);
			unset($data['menus'][$i]['menu_content']['name']);
			$data['menus'][$i]['menu_name'] = $menu['menu_name'];
			$data['menus'][$i]['menu_id'] = $menu['id'];
		    $data['menus'][$i]['menu_content'] = get_href($data['menus'][$i]['menu_content'], $pages);
	    }

	    //Загрузка head
	    $CI->load->model('mdl_basic_config');
		$basic_config = $CI->mdl_basic_config->getlist();
	    if(isset($basic_config[0])) if(isset($basic_config[0]['head_content'])) $data['head_content'] = str_replace('{{base_url}}', base_url(), $basic_config[0]['head_content']);

        //Загрузка комплектаций авто конфигруратора
        $CI->load->model("mdl_conf_cars");
        $data['conf_car'] = $CI->mdl_conf_cars->get('', array('cnfcar_id_page'=>$id));
        if(!empty($data['conf_car'])){
            $CI->load->model('mdl_conf_complect');
            $data['conf_complect'] = $CI->mdl_conf_complect->getlist('',3,'','', array('cmpl_car'=>$data['conf_car']['cnfcar_name']));
        }else{$data['conf_complect'] = array();}
        if(!empty($data['conf_complect'])){
            for($i=0; $i<count($data['conf_complect']); $i++){
                //массив всех удобств
                $elements = explode("^", $data['conf_complect'][$i]['cmpl_additcmf']);

                //массив доступных удобств
                $features = array();
                foreach ($elements as $e)
                {
                    $e = explode("|", $e);

                    //пополняем только если данное удобство доступно или имеет цену
                    if (trim($e[0])) $features[] = $e[0];
                }
                $data['conf_complect'][$i]['cmpl_additcmf'] = $features;
            }
        }

	    //Загрузка базовых параметров
		$this->load->model('mdl_basic_config');
		$basic_config = $this->mdl_basic_config->getlist();
		$data['basic_config'] = $basic_config[0];

        $query = $this->db->query("SELECT * FROM cm_page WHERE page_pattern!='main' && status='Опубликовано' ORDER BY page_number");
        $data['one'] = $query->result_array();
        $data['head'] = $this->load->view("blocks/head", $data, true);
        $data['header_content'] = $this->load->view("blocks/header_content", $data, true);
        $data['left_content'] = $this->load->view("blocks/left_content", $data, true);

        //Загрузка блоков
        $data['tab_menu'] = $this->load->view("blocks/tab_menu", $data, true);
        $data['right_content'] = $this->load->view("blocks/".$content, $data, true);
        $data['footer'] = $this->load->view("blocks/footer-index", $data, true);
        $this->load->view("index",$data);
    }

    /**
     * Вывод данных для табов, Описание, Комплектации и цены, Авто в наличии, Галерея
     */
    function tabs($t=0, $url=0, $tab=0, $page = 0)
    {
	     //загрузка страницы

	    if(! $url) $url = $this->uri->uri_string();
        if(! $page)
        {
	        $data['v'] = $this->mdl_add_page->get('', array('page_url'=> $url));
	        $this->load->model("mdl_add_page");
        }
		else $data['v'] = $page;
        $id = $data['v']['id'];

	    if(! $t) switch($tab)
	    {
			case 'complect': $t = 1;
			    break;
		    case 'cars-for-sale': $t = 2;
			    break;
		    case 'configurator': $t = 3;
			    break;
		    case 'galery': $t = 4;
			    break;
		    case 'otzyvy-pressy': $t = 5;
			    break;
		    case 'aksessuary': $t = 6;
			    break;
			default: $t = 0;
	    }

	    $titles = explode('|', $data['v']['seo_title']);
	    $keywords = explode('|', $data['v']['keywords']);
	    $descriptions = explode('|', $data['v']['description']);

	    if (count($keywords) > $t) $data['v']['keywords'] = $keywords[$t];
	    if (count($titles) > $t) $data['v']['seo_title'] = $titles[$t];
	    if (count($descriptions) > $t) $data['v']['description'] = $descriptions[$t];

	    //print_r($keywords);
	    //print_r($titles);
	    //print_r($descriptions);

	    //загрузка список страниц по рубрикам
	    $this->load->model("mdl_rubric");
        $rubrics = $this->mdl_rubric->getlist();

	    $CI = &get_instance();
	    $CI->load->model('mdl_add_page');
	    $pages = $CI->mdl_add_page->getlist(false, false, false, false, array("status"=>'Опубликовано'));

	    foreach($rubrics as $rubric)
	    {
		    $data['rubrics'][$rubric['rubric_name']] = array();

		    foreach($pages as $page)
			    if(strstr( $page['page_rubric'], $rubric['rubric_name'] ))
		            $data['rubrics'][$rubric['rubric_name']][] = $page;
	    }

        //Загрузка необходимого блока для отображения содержимого таба
        $content = 'right_content';
        if($t == 1){
            $data['activtab'] = 1;
            $content = 'complect-price';
            $data['complect_price'] = $content;
        }
        elseif($t == 2){
            $data['activtab'] = 2;
            if(isset($_GET['macros']) && isset($_GET['fda'])){
                //Загрузка авто в наличии по id
                $CI->load->model("mdl_formed_fda");
                $data['carsale'] = $CI->mdl_formed_fda->get($_GET['fda']);
                //Загрузка комплектации по id черз поле fda_macros_id в авто в наличии
                $CI->load->model("mdl_macros");
                $data['complect'] = $CI->mdl_macros->get($_GET['macros']);
                if(!empty($data['complect'])){
                    if(!empty($data['complect']['parametrs']))
                    $data['complect']['parametrs'] = explode(" | ", $data['complect']['parametrs']);
                }
                //Загрузка разделов дополнительных удобств
                $this->load->model("mdl_comfort");
                $data['parts'] = $CI->mdl_comfort->getlist(false, false, 'cmf_position', '', array('cmf_status'=>1));
                //Загрузка параметров дополнительных удобств
                $CI->load->model("mdl_formed");
                $data['params'] = $CI->mdl_formed->getlist(false, false, 'position', '', array('status'=>1));
                if(!empty($data['params'])){
                    for($i=0; $i<count($data['params']); $i++){
                        $data['params'][$i]['caroptions'] = explode(",", $data['params'][$i]['caroptions']);
                    }
                }
                $content = 'characteristics-car';
            }
            else{
                //Загрузка комплекта авто добавленных к странице
                $CI->load->model("mdl_completpage");
                $complPage = $CI->mdl_completpage->get('', array("id_page"=>$id));
                if(!empty($complPage)){
                    //загрузка данных авто в наличии по названию авто
                    $CI->load->model("mdl_formed_fda");
                    $data['formfda'] = $CI->mdl_formed_fda->getlist(false, false, '', '', array("fda_car"=>$complPage['car_tda']));
                    //загрузка комплектаций по id черз поле fda_macros_id в авто в наличии
                    $CI->load->model("mdl_macros");
                    if(!empty($data['formfda'])){
                        foreach($data['formfda'] as $v){
                            $data['macros'][] = $CI->mdl_macros->get($v['fda_macros_id']);
                        }
                    }
                }
                $content = 'product-table';
            }
        }
	    elseif($t == 3)
	    {

		    $data['activtab'] = 3;

	        function sort_sections($a, $b)
	        {
		        if ($a['parts_position'] == $b['parts_position']) return 0;
		        return ($a['parts_position'] < $b['parts_position']) ? -1 : 1;
	        }

	        function sort_parameters($a, $b)
	        {
		        if ($a['param_position'] == $b['param_position']) return 0;
		        return ($a['param_position'] < $b['param_position']) ? -1 : 1;
	        }


	        //находим авто для выбранной страницы
	        $CI->load->model("mdl_conf_cars");
	        $car = $CI->mdl_conf_cars->getSel('cnfcar_id_page',$id);
			if($car)
			{
				//находим комплектации для выбранного авто
				$CI->load->model("mdl_conf_complect");
				$complectations = $CI->mdl_conf_complect->getSelAll('cmpl_car',$car['cnfcar_name']);

				//$data['test'] = $complectations;

				if($complectations)
				{
					$section_premier_name = 'Базовые характеристики';

					foreach($complectations as $complectation)
					{
						//$data['test'] = $complectation;
//						$features = $complectation['cmpl_additcmf'];
//						if(! $features) $features = '-';
//						else $features = '<ul><li>'.str_replace('|','</li><li>',$features).'</li></ul>';

						$parameters[$complectation['cmpl_name']][$section_premier_name]['Тип кузова']['value'] = $complectation['cmpl_body'];
						$parameters[$complectation['cmpl_name']][$section_premier_name]['Привод']['value'] = $complectation['cmpl_drive'];
						$parameters[$complectation['cmpl_name']][$section_premier_name]['Коробка передач']['value'] = $complectation['cmpl_kpp'];
						$parameters[$complectation['cmpl_name']][$section_premier_name]['Двигатель']['value'] = $complectation['cmpl_engine'];
						$parameters[$complectation['cmpl_name']][$section_premier_name]['Тип двигателя']['value'] = $complectation['cmpl_type_eng'];
						$parameters[$complectation['cmpl_name']][$section_premier_name]['Объём']['value'] = $complectation['cmpl_volume_eng'];
						$parameters[$complectation['cmpl_name']][$section_premier_name]['Мощность']['value'] = $complectation['cmpl_power_eng'];
						$parameters[$complectation['cmpl_name']][$section_premier_name]['Год']['value'] = $complectation['cmpl_year'];
						$parameters[$complectation['cmpl_name']][$section_premier_name]['Пробег']['value'] = $complectation['cmpl_run']." ".$complectation['cmpl_mlskm'];
						//$parameters[$complectation['cmpl_name']][$section_premier_name]['Особенности']['value'] = $features;

						foreach($parameters[$complectation['cmpl_name']][$section_premier_name] as $key => $value)
						{
							if(! trim($value['value'])) $parameters[$complectation['cmpl_name']][$section_premier_name][$key]['value'] = '-';
							$parameters[$complectation['cmpl_name']][$section_premier_name][$key]['is_switch'] = '0';
						}

						$parameters[$complectation['cmpl_name']]['id'] = $complectation['id'];
						$parameters[$complectation['cmpl_name']]['base_price'] = $complectation['cmpl_price'];
						$parameters[$complectation['cmpl_name']]['values'] = explode('^', $complectation['cmpl_additcmf']);

//						echo "<pre>";
//						print_r($parameters[$complectation['cmpl_name']]['values']);
//						echo "</pre>";

						foreach ($parameters[$complectation['cmpl_name']]['values'] as $key => $value)
						{
							$parameters[$complectation['cmpl_name']]['values'][$key] = explode('|', $value);
						}

//						echo "<pre>";
//						print_r($parameters[$complectation['cmpl_name']]['values']);
//						echo "</pre>";
					}

					//$data['test'] = $parameters;

					//загрузка дополнительных удобств авто
					$CI->load->model("mdl_conf_additcmf_parts");
					$CI2 = &get_instance();
					$CI2->load->model('mdl_conf_additcmf_param');

					$sections[$complectation['cmpl_name']] = $CI->mdl_conf_additcmf_parts->getSelAll('parts_name_car',$car['cnfcar_name']);

					//$data['test'] = $sections;

					if($sections[$complectation['cmpl_name']])
					{
						if(is_array($sections[$complectation['cmpl_name']]))
						{
							usort($sections[$complectation['cmpl_name']],'sort_sections');
							foreach($sections[$complectation['cmpl_name']] as $section)
							{
								$values = $CI2->mdl_conf_additcmf_param->getSelAll('param_id_parts',$section['id']);

								//$data['test'] = $values;

								if(is_array($values))
								{
									usort($values, 'sort_parameters');
									foreach($values as $value)
									{
										if($value['param_is_list'])
										{
//											if(! $value['param_is_switch'])
//											{
//												if(trim($value['param_value']))
//												{
//													$value['param_value'] = explode('|',$value['param_value']);
//													if(count($value['param_value']) == 1) $value['param_value'] = $value['param_value'][0];
//												}
//												else $value['param_value'] = '-';
//											}

											foreach($complectations as $complectation)
											{
												$value['param_value'] = 0;
												//echo "Ищем имя параметра ". $value['param_name'];
												//echo "в комплектации ".$complectation['cmpl_name']."<br>";
												foreach($parameters[$complectation['cmpl_name']]['values'] as $key => $true_val)
												{
													//echo "Раздел ".$true_val[0];
													if($true_val[0] == $value['param_name'])
													{
														//echo "равен ";
														unset($true_val[0]);
														//print_r($true_val);
														if (count($true_val) > 1)
														{
															$value['param_value'] = '<ul><li>'.join('</li><li>',$true_val).'</li></ul>';
															//echo "и является списком.";
														}
														else
														{
															$value['param_value'] = $true_val[1];
															//echo "и не является списком.";
														}

													}
												}
												$parameters[$complectation['cmpl_name']][$section['parts_name']][$value['param_name']] = array('value' => $value['param_value'], 'is_switch' => $value['param_is_switch']);
											}
										}
									}
								}
							}
						}
					}

//					foreach($complectations as $complectation)
//						{
//							foreach($parameters[$complectation['cmpl_name']]['values'] as $key => $true_val)
//							{
//								if($true_val[0] == $value['param_name'])
//								{
//									unset($true_val[0]);
//									//print_r($true_val);
//									if (count($true_val) > 1)
//									{
//										$value['param_value'] = '<ul><li>'.join('</li><li>',$true_val).'</li></ul>';
//									}
//									else
//									{
//										$value['param_value'] = $true_val[1];
//									}
//								}
//							}
//							$parameters[$complectation['cmpl_name']][$section['parts_name']][$value['param_name']] = array('value' => $value['param_value'], 'is_switch' => $value['param_is_switch']);
//						}

					foreach($complectations as $complectation)
					{
						unset($parameters[$complectation['cmpl_name']]['values']);
					}


	//			if($complectations)
	//			{
	//				foreach($complectations as $complectation)
	//				{
	//					$sections[$complectation['cmpl_name']] = $CI->mdl_conf_additcmf_parts->getSelAll('parts_cmpl_id',$complectation['id']);
	//					$parameters[$complectation['cmpl_name']]['id'] = $complectation['id'];
	//					$parameters[$complectation['cmpl_name']]['base_price'] = $complectation['cmpl_price'];
	//					if(is_array($sections[$complectation['cmpl_name']]))
	//					{
	//						usort($sections[$complectation['cmpl_name']],'sort_sections');
	//						foreach($sections[$complectation['cmpl_name']] as $section)
	//						{
	//							if ($section['parts_status'])
	//							{
	//								$values = $CI2->mdl_conf_additcmf_param->getSelAll('param_id_parts',$section['id']);
	//								usort($values, 'sort_parameters');
	//								foreach($values as $value)
	//								{
	//									if($value['param_status'])
	//									{
	//										$parameters[$complectation['cmpl_name']][$section['parts_name']][$value['param_name']] = array('value' => $value['param_value'], 'is_switch' => $value['param_is_switch']);
	//									}
	//								}
	//							}
	//						}
	//					}
	//				}

					//$data['test'] = $parameters;
					$data['complectations'] = $parameters;
					$data['error'] = false;
				}
				else $data['error'] = 'Для данного авто нет комплектаций';
			}
			else $data['error'] = 'С данной страницей не связано авто с комплектациями';
            $content = 'configurator-view';

        }
        elseif($t == 4){
            $data['activtab'] = 4;
            //Загрузка фотографий
            $CI->load->model("mdl_photogalery");
            $galery_items = $CI->mdl_photogalery->getSelAll('id_page', $id);
	        $data['galery_flash'] = $data['galery_exterior'] = $data['galery_interior'] = $data['galery_video'] = array();
	        foreach($galery_items as $item)
	        {
		        switch($item['file_type'])
		        {
			        case 1 : $data['galery_flash'][] = $item; break;
			        case 2 : $data['galery_exterior'][] = $item; break;
			        case 3 : $data['galery_interior'][] = $item; break;
			        case 4 : $data['galery_video'][] = $item;
				    case 5 : $data['galery_flash_html'] = $item['file_name'];
		        }
	        }
            $content = 'photo-list';
//	        echo "<pre>";
//                print_r($galery_exterior);
//	            echo "------------------";
//	            print_r($galery_interior);
//	            echo $data['galery_flash_html'];
//	        echo "</pre>";
        }
        else if($t == 5){
            $data['activtab'] = 5;
            $content = 'articles';
            $data['articles'] = $content;
        }
        elseif($t == 6){
            $data['activtab'] = 6;
            $content = 'accessories';

	        $CI->load->model("mdl_accessories");
	        $data['access'] = $CI->mdl_accessories->getlist(false, false, false, false, array("page_anchor"=>$data['v']['title']));

	        $flag = 1;
	        $types = array();
	        foreach ($data['access'] as $item)
	        {
//		        echo "Начинаем проверку для типа ".$item['access_type'].":\n";
		        foreach ($types as $type)
		        {
					if($type == $item['access_type'])
					{
						//echo "&nbsp;Такой тип уже есть в копилке!\n";
						$flag = 0;
					}
		        }

		        if($flag)
		        {
			        $types[] = $item['access_type'];
//			        echo "&nbsp;Добавляем тип в копилку!\nКопилка:\n";
//			        echo "<pre>";
//			        print_r($types);
//			        echo "</pre>\n\n";
		        }
		        $flag = 1;
	        }

	        $data['access_types'] = $types;
        }

        else
        {
	        redirect(base_url().'error-404/', 'location', 301);
        }

        //Загрузка модуля
        $CI->load->model("mdl_module");
        $data['module'] = $CI->mdl_module->getlist(false, false, 'modul_position', '', array("module_status"=>"Опубликовано"));

	    //загрузка меню
	    $CI->load->model('mdl_menu');
		$menus = $CI->mdl_menu->getlist();
	    function get_href($menu, $pages)
		{
			if (is_array($menu))
				foreach($menu as $k => $m)
				{
					foreach($pages as $p)
					{
						if($p['id'] == $m['href'])
						{
							$menu[$k]['id'] = $menu[$k]['href'];
							$menu[$k]['href'] = $p['page_url'];
							break;
						}
					}
					if(isset($m['sub_options'])) $menu[$k]['sub_options'] = get_href($m['sub_options'], $pages);
				}
			return $menu;
		}
	    foreach($menus as $i => $menu)
	    {
			$data['menus'][$i]['menu_content'] = json_decode($menu['menu_structure'], TRUE);
			unset($data['menus'][$i]['menu_content']['name']);
			$data['menus'][$i]['menu_name'] = $menu['menu_name'];
			$data['menus'][$i]['menu_id'] = $menu['id'];
		    $data['menus'][$i]['menu_content'] = get_href($data['menus'][$i]['menu_content'], $pages);
	    }

	    //Загрузка базовых параметров
		$CI->load->model('mdl_basic_config');
		$basic_config = $CI->mdl_basic_config->getlist();
		$data['basic_config'] = $basic_config[0];

		//Загрузка head
	    $CI->load->model('mdl_basic_config');
		$basic_config = $CI->mdl_basic_config->getlist();
	    if(isset($basic_config[0])) if(isset($basic_config[0]['head_content'])) $data['head_content'] = str_replace('{{base_url}}', base_url(), $basic_config[0]['head_content']);


        //Загрузка страниц в блок header_content left_content
        $this->CI = &get_instance();
        $query = $this->db->query("SELECT * FROM cm_page WHERE page_pattern!='main' && status='Опубликовано' ORDER BY page_number");
        $data['one'] = $query->result_array();
        $data['head'] = $this->load->view("blocks/head", $data, true);
        $data['header_content'] = $this->load->view("blocks/header_content", $data, true);
        //$data['left_content'] = $this->load->view("blocks/left_content", $data, true);

        //Загрузка блоков
        $data['tab_menu'] = $this->load->view("blocks/tab_menu", $data, true);
        $data['right_content'] = $this->load->view("blocks/".$content, $data, true);
        $data['footer'] = $this->load->view("blocks/footer-index", $data, true);
        $this->load->view("index",$data);

    }

    /**
     * Добавление заявки на ТО
     */
    function callclient_to($page_url)
    {
        $CI = &get_instance();
        $CI->load->model("mdl_callclient_to");
        if($CI->mdl_callclient_to->add() !== false){

	        //Загрузка базовых параметров
			$CI->load->model('mdl_basic_config');
			$basic_config = $CI->mdl_basic_config->getlist();

            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $CI->load->model("mdl_add_user");
            $managers = $CI->mdl_add_user->getlist(false, false, '', '', array('status'=>'Менеджер ремонта'));
            $manager_message = $this->load->view('blocks/template_message/to_manager','',true);
            foreach($managers as $mng){
                if($mng['email']){
                    $mng['email'] = explode(" | ", $mng['email']);
                    foreach($mng['email'] as $m){
                        $this->email->from($_POST['call_email'], $_POST['call_fio']);
                        $this->email->to($m);
                        $this->email->subject("Заявка на ремонт от " . $_POST['call_fio'] . " для " . $_POST['call_modelcar']);
                        $this->email->message($manager_message);
                        if(!($this->email->send())){
                            echo "Ошибка отправки сообщения менеджеру";
                        }
                    }
                }
            }
            if($_POST['call_email'] !== ''){
                $this->load->model('mdl_footmsg');
                $data['footmsg'] = $this->mdl_footmsg->get('', array('type'=>'general'));
                $client_mesage = $this->load->view('blocks/template_message/to_client',$data,true);
                $this->email->from('noreply@mail.ru', $basic_config[0]['co_name']);
                $this->email->to($_POST['call_email']);
                $this->email->subject('Заявка на ремонт');
                $this->email->message($client_mesage);
                if($this->email->send()){
                    redirect(base_url().$page_url.'?succ=1', 'location', 301);
                }
                else{
                    echo 'Ошибка отправки сообщения клиенту';
                }
            }
            else{
                redirect(base_url().$page_url.'?succ=1', 'location', 301);
            }
        }
        else{
            redirect(base_url().$page_url.'?succ=0', 'location', 301);
        }
    }

    /**
     * Добавление заявки на тестдрайв
     */
    function testdrive($page_url)
    {
        $CI = &get_instance();

//	    echo $page_url."<br>";
//	    echo '<pre>';
//	        print_r($_POST);
//	    echo '</pre>';

	    //phpinfo();

	    //Загрузка базовых параметров
		$CI->load->model('mdl_basic_config');
		$basic_config = $CI->mdl_basic_config->getlist();

        $CI->load->model("mdl_callclient_testdrive");
        if($CI->mdl_callclient_testdrive->add() !== false){
//            $config['mailtype'] = 'html';
//	        $mail_config['charset']      = 'utf-8';
//			$mail_config['mailtype']    = 'text';
//			$mail_config['crlf']        = "\r\n";
//			$mail_config['newline']     = "\r\n";
//			$mail_config['wrapchars']   = 76;
//			$mail_config['wordwrap']    = TRUE;
            //$this->email->initialize($config);
            $CI->load->model("mdl_add_user");
            $managers = $CI->mdl_add_user->getlist(false, false, '', '', array('status'=>'Менеджер тестдрайва'));
            $manager_message = $this->load->view('blocks/template_message/testdrive_manager','',true);

            foreach($managers as $mng){
                if($mng['email']){
                    $mng['email'] = explode(" | ", $mng['email']);
                    foreach($mng['email'] as $m){
                        $this->email->from($_POST['call_email'], $_POST['call_name'].' '.$_POST['call_lastname']);
                        $this->email->to($m);
                        $this->email->subject("Заявка на тестдрайв " . $_POST['call_name'] . $_POST['call_lastname'] . " для " . $_POST['call_model']);
                        $this->email->message($manager_message);
                        if(!($this->email->send())){
                            echo "Ошибка отправки сообщения менеджеру";
                        }
                    }
                }
            }
            if($_POST['call_email'] !== ''){
                $this->load->model('mdl_footmsg');
                $data['footmsg'] = $this->mdl_footmsg->get('', array('type'=>'general'));
                $client_mesage = $this->load->view('blocks/template_message/testdrive_client',$data,true);
                $this->email->from('noreply@mail.ru', $basic_config[0]['co_name']);
                $this->email->to($_POST['call_email']);
                $this->email->subject('Заявка на тестдрайв');
                $this->email->message($client_mesage);
                if($this->email->send()){
                    redirect(base_url().$page_url, 'location', 301);
                }
                else{
                    echo 'Ошибка отправки сообщения клиенту';
                }
	            //echo $this->email->print_debugger();
            }
            else{
                redirect(base_url().$page_url, 'location', 301);
            }
        }
        else{
                redirect(base_url().$page_url, 'location', 301);
        }
    }

    //Доабвлеие заявки трейдын
    function tradein($page_url)
    {
        $CI = &get_instance();
        $CI->load->model("mdl_callclient_tradein");
        if($CI->mdl_callclient_tradein->add() !== false){
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $CI->load->model("mdl_add_user");
            $managers = $CI->mdl_add_user->getlist(false, false, '', '', array('status'=>'Менеджер трейд-ин'));
            $manager_message = $this->load->view('blocks/template_message/tradein_manager','',true);
            foreach($managers as $mng){
                if($mng['email']){
                    $mng['email'] = explode(" | ", $mng['email']);
                    foreach($mng['email'] as $m){
                        $this->email->from($_POST['call_email'], $_POST['call_fio']);
                        $this->email->to($m);
                        $this->email->subject("Заявка на трейд-ин " . $_POST['call_fio'] . " для " . $_POST['call_modelcar']);
                        $this->email->message($manager_message);
                        if(!($this->email->send())){
                            echo "Ошибка отправки сообщения менеджеру";
                        }
                    }
                }
            }
            if($_POST['call_email']!==''){

	            //Загрузка базовых параметров
	            $CI = &get_instance();
	            $CI->load->model('mdl_basic_config');
				$basic_config = $CI->mdl_basic_config->getlist();

                $this->load->model('mdl_footmsg');
                $data['footmsg'] = $this->mdl_footmsg->get('', array('type'=>'general'));
                $client_mesage = $this->load->view('blocks/template_message/tradein_client',$data,true);
                $this->email->from('noreply@mail.ru', $basic_config[0]['co_name']);
                $this->email->to($_POST['call_email']);
                $this->email->subject('Заявка на трейд-ин');
                $this->email->message($client_mesage);
                if($this->email->send()){
                    redirect(base_url().$page_url.'?form='.$_GET['car'].'&s=1', 'location', 301);
	            }
                else{
                    echo 'Ошибка отправки сообщения клиенту';
                }
            }
            else{
                redirect(base_url().$page_url.'?form='.$_GET['car'].'&s=1', 'location', 301);
            }
        }
        else{
//	        echo "<pre>";
//	        print_r($_POST);
//	        echo "</pre>";
            redirect(base_url().$page_url.'?form='.$_GET['car'].'&s=0', 'location', 301);
        }
    }


    //Добавление Заявки на авто в наличии
    /*function carsale($id)
    {
        $CI = &get_instance();
        $CI->load->model("mdl_callclient_carsale");
        if($CI->mdl_callclient_carsale->add() !== false){
            $CI->load->model("mdl_add_user");
            $manager = $CI->mdl_add_user->getSelAll('status','Менеджер ТО');
            $manager_message = $this->load->view('blocks/template_message/carsale_manager','',true);
            foreach($manager as $m){
                $_POST['call_email'] = ($_POST['call_email'] !== '')?$_POST['call_email']:'none_email';
                $this->email->from($_POST['call_email'], $_POST['call_fio']);
                $this->email->to($m['email']);
                $this->email->subject("Заявка на авто в наличии " . $_POST['call_fio'] . " для " . $_POST['call_modelcar']);
                $this->email->message($manager_message);
                if(!($this->email->send())){
                    echo "Ошибка отправки сообщения менеджеру";
                }
            }
            if($_POST['call_email'] !== ''){
                $client_mesage = $this->load->view('blocks/template_message/carsale_client','',true);
                $this->email->from('tradein@mail.ru', 'CITROEN');
                $this->email->to($_POST['call_email']);
                $this->email->subject('Заявка на авто в наличии');
                $this->email->message($client_mesage);
                if($this->email->send()){
                    redirect(base_url().'frontend/carsale/'.$id.'?succ=1&macros='.$_GET['macros'].'&fda='.$_GET['fda']);
                }
                else{
                    echo 'Ошибка отправки сообщения клиенту';
                }
            }
            else{
                redirect(base_url().'frontend/carsale/'.$id.'?succ=1&macros='.$_GET['macros'].'&fda='.$_GET['fda']);
            }
        }
        else{
            $CI = &get_instance();
            $data['activtab'] = 2;
            $CI->load->model("mdl_add_page");
            //загрузка страницы по id
            $data['v'] = $CI->mdl_add_page->get($id);

            //Загрузка данных формы ФДА
            $CI->load->model("mdl_formed_fda");
            $data['formfda'] = $CI->mdl_formed_fda->get($_GET['fda']);
            //Загрузка комплектации из формы ФДА
            $CI->load->model("mdl_macros");
            $data['macros'] = $CI->mdl_macros->get($_GET['macros']);
            if(!empty($data['macros']['comfort_a']))
            $data['macros']['comfort_a'] = explode(" | ", $data['macros']['comfort_a']);
            if(!empty($data['macros']['comfort_b']))
            $data['macros']['comfort_b'] = explode(" | ", $data['macros']['comfort_b']);
            if(!empty($data['macros']['comfort_c']))
            $data['macros']['comfort_c'] = explode(" | ", $data['macros']['comfort_c']);
            if(!empty($data['macros']['comfort_d']))
            $data['macros']['comfort_d'] = explode(" | ", $data['macros']['comfort_d']);
            //Загрузка всех данных комфорта
            $CI->load->model("mdl_formed");
            $data['formed'] = $CI->mdl_formed->getlist();

            //Загрузка модуля
            $CI->load->model("mdl_module");
            $data['module'] = $CI->mdl_module->getSelAll("module_status","Опубликовано");

            //Загрузка страниц в блок header_content left_content
            $this->CI = &get_instance();
            $query = $this->db->query("SELECT * FROM cm_page WHERE page_pattern!='main' && status='Опубликовано' ORDER BY page_number");
            $data['one'] = $query->result_array();
            $data['head'] = $this->load->view("blocks/head", $data, true);
            $data['header_content'] = $this->load->view("blocks/header_content", $data, true);
            $data['left_content'] = $this->load->view("blocks/left_content", $data, true);
            //Загрузка блоков
            $data['tab_menu'] = $this->load->view("blocks/tab_menu", $data, true);
            $data['form_TO'] = $this->load->view("blocks/form-TO", $data, true);
            $data['form_testdrive'] = $this->load->view("blocks/form-testdrive", '', true);
            $data['right_content'] = $this->load->view("blocks/characteristics-car", $data, true);
            $data['footer'] = $this->load->view("blocks/footer", $data, true);
            $this->load->view("index",$data);
        }
    }*/

    //Заявки на обсчет кузовного ремонта
    function body_repair($page_url)
    {
        $this->load->model("mdl_callclient_body_repair");
        if($this->mdl_callclient_body_repair->add() !== false){
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $this->load->model("mdl_add_user");
            $managers = $this->mdl_add_user->getlist(false, false, '', '', array('status'=>'Контент-менеджер'));
            $manager_message = $this->load->view('blocks/template_message/body_repair_manager','',true);
            $emailclient = ($_POST['call_email'] !== '')?$_POST['call_email']:'not_email';
            foreach($managers as $mng){
                if($mng['email']){
                    $mng['email'] = explode(" | ", $mng['email']);
                    foreach($mng['email'] as $m){
                        $this->email->from($emailclient, $_POST['call_fio']);
                        $this->email->to($m);
                        $this->email->subject("Заявка на рассчет кузовного ремонта " . $_POST['call_fio'] . " для " . $_POST['call_modelcar']);
                        $this->email->message($manager_message);
                        if(!($this->email->send())){
                            echo "Ошибка отправки сообщения менеджеру";
                        }
                    }
                }
            }
            if($_POST['call_email'] !== ''){

	            //Загрузка базовых параметров
				$CI = &get_instance();
				$CI->load->model('mdl_basic_config');
				$basic_config = $CI->mdl_basic_config->getlist();

                $this->load->model('mdl_footmsg');
                $data['footmsg'] = $this->mdl_footmsg->get('', array('type'=>'general'));
                $client_mesage = $this->load->view('blocks/template_message/body_repair_client',$data,true);
                $this->email->from('noreply@mail.ru', $basic_config[0]['co_name']);
                $this->email->to($_POST['call_email']);
                $this->email->subject('Заявка на рассчет кузовного ремонта');
                $this->email->message($client_mesage);
                if($this->email->send()){
                    redirect(base_url().$page_url.'?s=1', 'location', 301);
                }
                else{
                    echo 'Ошибка отправки сообщения клиенту';
                }
            }
            else{
                redirect(base_url().$page_url.'?s=1', 'location', 301);
            }
        }
        else{
            redirect(base_url().$page_url.'?s=0', 'location', 301);
        }
    }

    //Анкета для продления договоров страхования
    function assurante($page_url)
    {
        $this->load->model("mdl_callclient_assurance");
        if(($this->mdl_callclient_assurance->add()) !== false){
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $this->load->model("mdl_add_user");
            $managers = $this->mdl_add_user->getlist(false, false, '', '', array('status'=>'Менеджер договоров'));

            $message_manager = $this->load->view("blocks/template_message/assurance_manager", '', true);
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            foreach($managers as $mng){
                if($mng['email']){
                    $mng['email'] = explode(" | ", $mng['email']);
                    foreach($mng['email'] as $mng){

                        $this->email->from('not_email', $_POST['call_fio']);
                        $this->email->to($mng);
                        $this->email->subject('Анкета для продления договоров страхования');
                        $this->email->message($message_manager);
                        if(!($this->email->send())){
                            echo "Ошибка отправки сообщения менеджеру";
                        }
                    }
                }
            }
            redirect(base_url().$page_url.'?s=1', 'location', 301);
        }
        else{
            redirect( base_url().$page_url.'?s=0', 'location', 301);
        }
    }

    //Выкуп автомобилей
    function boyoutCar($page_url)
    {
        $this->load->model("mdl_callclient_boyout_car");
        if(($this->mdl_callclient_boyout_car->add()) !== false){
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $this->load->model("mdl_add_user");
            $managers = $this->mdl_add_user->getlist(false, false, '', '', array('status'=>'Менеджер выкупа авто'));

            $message_manager = $this->load->view("blocks/template_message/boyoutcar_manager", '', true);
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            foreach($managers as $mng){
                if($mng['email']){
                    $mng['email'] = explode(" | ", $mng['email']);
                    foreach($mng['email'] as $mng){
                        $this->email->from($_POST['call_email'], $_POST['call_fio']);
                        $this->email->to($mng);
                        $this->email->subject('Форма выкупа автомобиля');
                        $this->email->message($message_manager);
                        if(!($this->email->send())){
                            echo "Ошибка отправки сообщения менеджеру";
                        }
                    }
                }
            }

	        //Загрузка базовых параметров
			$CI = &get_instance();
            $CI->load->model('mdl_basic_config');
			$basic_config = $CI->mdl_basic_config->getlist();

            $this->load->model('mdl_footmsg');
            $data['footmsg'] = $this->mdl_footmsg->get('', array('type'=>'general'));
            $message_client = $this->load->view("blocks/template_message/boyoutcar_client", $data, true);
            $this->email->from('noreply@mail.ru', $basic_config[0]['co_name']);
            $this->email->to($_POST['call_email']);
            $this->email->subject('Выкуп автомобиля');
            $this->email->message($message_client);
            if(!($this->email->send())){
                echo "Ошибка отправки сообщения клиенту";

            }
            else{
                redirect(base_url().$page_url.'?s=1', 'location', 301);
            }
        }
        else{
            redirect(base_url().$page_url, 'location', 301);
        }
    }


    //Заявка на кредит
    function credits($page_url)
    {
        $this->load->model("mdl_callclient_credits");
        if(($this->mdl_callclient_credits->add()) !== false){
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $this->load->model("mdl_add_user");
            $managers = $this->mdl_add_user->getlist(false, false, '', '', array('status'=>'Менеджер кредита'));

            $message_manager = $this->load->view("blocks/template_message/credits_manager", '', true);
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            foreach($managers as $mng){
                if($mng['email']){
                    $mng['email'] = explode(" | ", $mng['email']);
                    foreach($mng['email'] as $mng){
                        $this->email->from($_POST['call_email'], $_POST['call_family'].' '.$_POST['call_name'].' '.$_POST['call_patronumic']);
                        $this->email->to($mng);
                        $this->email->subject('Заявка на кредит');
                        $this->email->message($message_manager);
                        if(!($this->email->send())){
                            echo "Ошибка отправки сообщения менеджеру";break;
                        }
                    }
                }
            }

			//Загрузка базовых параметров
			$CI = &get_instance();
            $CI->load->model('mdl_basic_config');
			$basic_config = $CI->mdl_basic_config->getlist();

            $this->load->model('mdl_footmsg');
            $data['footmsg'] = $this->mdl_footmsg->get('', array('type'=>'general'));
            $message_client = $this->load->view("blocks/template_message/credits_client", $data, true);
            $this->email->from('noreply@mail.ru', $basic_config[0]['co_name']);
            $this->email->to($_POST['call_email']);
            $this->email->subject('Заявка на кредит');
            $this->email->message($message_client);
            if(!($this->email->send())){
                echo "Ошибка отправки сообщения клиенту";
            }
            else{
                redirect(base_url().$page_url.'?s=1', 'location', 301);
            }
        }
        else{
	        //print_r($_POST);
            redirect(base_url().$page_url.'?s=0', 'location', 301);
        }

    }

    //Узнай свой бонус
    function bonus($page_url)
    {
        $this->load->model("mdl_callclient_bonus");
        if(($this->mdl_callclient_bonus->add()) !== false){
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $this->load->model("mdl_add_user");
            $managers = $this->mdl_add_user->getlist(false, false, '', '', array('status'=>'Менеджер бонуса'));

            $message_manager = $this->load->view("blocks/template_message/vigoda_manager", '', true);
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            foreach($managers as $mng){
                if($mng['email'] !== ''){
                    $mng['email'] = explode(" | ", $mng['email']);
                    foreach($mng['email'] as $mng){
                        $this->email->from($_POST['call_email'], $_POST['call_fio']);
                        $this->email->to($mng);
                        $this->email->subject('Узнать свой бонус');
                        $this->email->message($message_manager);
                        if(!($this->email->send())){
                            echo "Ошибка отправки сообщения менеджеру";break;
                        }
                    }
                }
                else{redirect();}
            }

	        //Загрузка базовых параметров
			$CI = &get_instance();
            $CI->load->model('mdl_basic_config');
			$basic_config = $CI->mdl_basic_config->getlist();

            $this->load->model('mdl_footmsg');
            $data['footmsg'] = $this->mdl_footmsg->get('', array('type'=>'general'));
            $message_client = $this->load->view("blocks/template_message/vigoda_klient", $data, true);
            $this->email->from('noreply@mail.ru', $basic_config[0]['co_name']);
            $this->email->to($_POST['call_email']);
            $this->email->subject('Узнать свой бонус');
            $this->email->message($message_client);
            if(!($this->email->send())){
                echo "Ошибка отправки сообщения клиенту";
            }
            else{
                redirect(base_url().$page_url, 'location', 301);
            }
        }
        else{
            redirect(base_url().$page_url, 'location', 301);
        }
    }

    //Форма запроса на наличие запасных запчастей
    function zapchasti($page_url)
    {
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $this->load->model("mdl_add_user");

        if($this->input->post('call_email'))
            $call_email = $this->input->post('call_email');
        else
            $call_email = 'not_email';

        $managers = $this->mdl_add_user->getlist(false, false, '', '', array('status'=>'Менеджер запчастей'));
        $message_manager = $this->load->view("blocks/template_message/zapchasti_manager", '', true);
        foreach($managers as $mng){
            if($mng['email'] !== ''){
                $mng['email'] = explode(" | ", $mng['email']);
                foreach($mng['email'] as $mng){
                    $this->email->from($call_email, $_POST['call_family'].' '.$_POST['call_name']);
                    $this->email->to($mng);
                    $this->email->subject('Запрос на наличие запасных частей (Шевроле)');
                    $this->email->message($message_manager);
                    if(!($this->email->send())){
                        echo "Ошибка отправки сообщения менеджеру";break;
                    }
                }
            }
            else{redirect();}
        }
        //если не указали email
        if(!$this->input->post('call_email')){redirect(base_url().$page_url, 'location', 301);}
        //Загрузка базовых параметров
        $CI = &get_instance();
        $CI->load->model('mdl_basic_config');
        $basic_config = $CI->mdl_basic_config->getlist();
        //Загрузка футера
        $this->load->model('mdl_footmsg');
        $data['footmsg'] = $this->mdl_footmsg->get('', array('type'=>'general'));

        $message_client = $this->load->view("blocks/template_message/zapchasti_klient", $data, true);
        $this->email->from('noreply@mail.ru', (!empty($basic_config))?$basic_config[0]['co_name']:'Chevrolet');
        $this->email->to($call_email);
        $this->email->subject('Запрос на наличие запасных частей (Шевроле)');
        $this->email->message($message_client);
        if(!($this->email->send())){
            echo "Ошибка отправки сообщения клиенту";
        }
        else{
            redirect(base_url().$page_url, 'location', 301);
        }
    }

    //Форма заявки на авто
    function carrequest($page_url)
    {
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $this->load->model("mdl_add_user");

        if($this->input->post('call_email'))
            $call_email = $this->input->post('call_email');
        else
            $call_email = 'not_email';

        $managers = $this->mdl_add_user->getlist(false, false, '', '', array('status'=>'Менеджер заявок авто'));
        $message_manager = $this->load->view("blocks/template_message/carrequest_manager", '', true);
        foreach($managers as $mng){
            if($mng['email'] !== ''){
                $mng['email'] = explode(" | ", $mng['email']);
                foreach($mng['email'] as $mng){
                    $this->email->from($call_email, $_POST['call_family'].' '.$_POST['call_name']);
                    $this->email->to($mng);
                    $this->email->subject('Заявка на авто');
                    $this->email->message($message_manager);
                    if(!($this->email->send())){
                        echo "Ошибка отправки сообщения менеджеру";break;
                    }
                }
            }
            else{redirect();}
        }
        //если не указали email
        if(!$this->input->post('call_email')){redirect(base_url().$page_url, 'location', 301);}
        //Загрузка базовых параметров
        $CI = &get_instance();
        $CI->load->model('mdl_basic_config');
        $basic_config = $CI->mdl_basic_config->getlist();
        //Загрузка футера
        $this->load->model('mdl_footmsg');
        $data['footmsg'] = $this->mdl_footmsg->get('', array('type'=>'general'));

        $message_client = $this->load->view("blocks/template_message/carrequest_klient", $data, true);
        $this->email->from('noreply@mail.ru', (!empty($basic_config))?$basic_config[0]['co_name']:'Chevrolet');
        $this->email->to($call_email);
        $this->email->subject('Заявка на авто');
        $this->email->message($message_client);
        if(!($this->email->send())){
            echo "Ошибка отправки сообщения клиенту";
        }
        else{
            redirect(base_url().$page_url, 'location', 301);
        }
    }















	function print_accessories($id)
	{
		$this->load->model('mdl_accessories');
		$data['accessories'] = $this->mdl_accessories->get($id);
		$this->load->view('print/print_access_view', $data);
	}

	//дополнительная информация для пункта "автомобили"
	function get_menu()
	{
		//echo $_POST['top_cars'];
		if(isset($_POST['top_cars']))
		{
			$top_cars = explode('|', $_POST['top_cars']);

			$CI = &get_instance();
			$CI->load->model("mdl_add_page");
			$pages = $CI->mdl_add_page->getlist(false, false, false, false, array("status"=>"Опубликовано", "page_pattern"=>"car"));
			$CI->load->model("mdl_conf_complect");
			$complectations = $CI->mdl_conf_complect->getlist();
			$CI->load->model("mdl_conf_cars");
			$complect_cars = $CI->mdl_conf_cars->getlist();

			if(count($pages) > -1)
			{
				$i = 0;
				foreach ($top_cars as $m)
				{
					$flag = 0;
					//для каждого авто в пункте меню выбираем самбнейл страницы
					foreach ($pages as $p)
					{
						if ($m == $p['id'])
						{
							if($p['page_thumbnail'] !== '0')
							{
								$menu_data[$i]['thumbnail'] = $p['page_thumbnail'];
								$menu_data[$i]['title'] = $p['title'];
								$menu_data[$i]['id'] = $p['id'];
								$menu_data[$i]['url'] = $p['page_url'];
								$flag = 1;
							}
							break;
						}
					}

					//для каждого авто в пункте меню выбираем наименьшую цену из комплектаций, которые на него завязаны
					//-----------------------
					$menu_data[$i]['price'] = 0;
					//находим авто конфигуратора, которое завязано на эту страницу
					foreach ($complect_cars as $c) if ($m == $c['cnfcar_id_page']) $car_name = $c['cnfcar_name'];

					if (isset($car_name))
					{
						foreach ($complectations as $c) if ($car_name == $c['cmpl_car'])
						{
							if(! $menu_data[$i]['price']) $menu_data[$i]['price'] = $c['cmpl_price'];
							elseif($menu_data[$i]['price'] > $c['cmpl_price']) $menu_data[$i]['price'] = $c['cmpl_price'];
						}
						if($menu_data[$i]['price']) $flag = 1;
						unset($car_name);
					}
					//-----------------------
					if ($flag) $i++;
				}

				if (isset($menu_data)) echo json_encode($menu_data);
				else echo false;
			}
			else echo false;
		}
		else echo false;
	}


    function test_top_menu()
    {

        $this->load->view("blocks/top-menu");
    }
}
