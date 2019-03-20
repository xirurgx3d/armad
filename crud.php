<?php
/**
 * @author allexe
 * @copyright 2010
 * 26.8.2010
 * Описание файла: Общая модель для работы с БД
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Crud extends CI_Model
{
 
    public $table = ''; //Имя таблицы
 
    public $idkey = 'id'; //Ключ ID
 
    public $add_rules = array(); //Правила валидации для добавления
 
    public $edit_rules = array(); //Правила валидации для редактирования
	
	public $del_rules_user = array();
	
	public $user = 'username';
 
    //Конструктор
    function Crud()
    {
        parent::__Construct();
    }
 
    /**
     * Функция добавления баннера в БД
     */

	function add_banner($banner_data)
	{
		$this->db->insert($this->table, $banner_data);
	}

	/**
     * Функция добавления записи в БД
     */
    function add()
    {
 
        $this->form_validation->set_rules($this->add_rules);
        if ($this->form_validation->run()) {
            $data = array();
            foreach ($this->add_rules as $one) {
                $f = $one['field'];

				if($f == 'password'){$password = $this->input->post($f);}
				elseif($f == 'password_p'){
					$password_p = $this->input->post($f);
					if($password == $password_p){$data['password']=sha1(md5($password));}else{return false;}
				}
                elseif($f == 'page_main'){
                    if($this->input->post($f) == 1){
                        $getresult = $this->getSel('page_main',1);
                        if(!empty($getresult)){
                            $getresult['page_main'] = 0;
                            $this->editSel('id',$getresult['id'],$getresult);
                            $data[$f] = $this->input->post("page_main");
                        }
                        else{$data[$f] = $this->input->post("page_main");}
                    }
                    else{$data[$f] = $this->input->post("page_main");}

                }
                elseif($f == 'call_listworks'){
                    $arr = $this->input->post($f);
                    $data[$f] = $this->implodes($arr);
                }
				else{
                    $data[$f] = $this->input->post($f);
                }
            }
            $this->db->insert($this->table, $data);
            return $this->db->insert_id(); //Возвращает номер последнего id
        } else {
            return false;
        }
    }

    /**
     * Добавлении опции для автомобилей
     */
    function optionsCars()
    {
        $this->form_validation->set_rules($this->add_rules);
        if($this->form_validation->run()){
            $data = array();
            foreach($this->add_rules as $one){
                $f = $one['field'];
                if($f == 'caroptions' || $f == 'name_car'){
                    $options = $this->input->post($f);
                    $key = $f;
                }
                else{
                    $data[$f] = $this->input->post($f);
                }
            }
            foreach($options as $oneoption){
                $data[$key] = strip_tags($oneoption);
                $this->db->insert($this->table,$data);
                $arrayId[] = $this->db->insert_id();
            }
            return $arrayId;
        }
        else{
            return false;
        }
    }

    /**
     * Функция для редактирования
     */
    function edit($id)
    {
        $this->form_validation->set_rules($this->edit_rules);
        if ($this->form_validation->run()) {
            $data = array();
            foreach ($this->edit_rules as $one) {
                $f = $one['field'];                
				if($f == 'password'){$password = $this->input->post($f);}
				elseif($f == 'password_p'){
					$password_p = $this->input->post($f);
					if($password == $password_p && $password !== '' && $password_p !== ''){
						$pass = $this->get($id);
						if($pass['password'] == sha1(md5($this->input->post('agedpassword')))){
                            $data['password'] = sha1(md5($password_p));
                        }else{return 1;}
					}
                    elseif($password == '' && $password_p == ''){
                        $pass = $this->get($id);
                        $data['password'] = $pass['password'];
                    }
                    else{
                        return 2;}
				}
                elseif($f == 'page_main'){
                    if($this->input->post($f) == 1){
                        $getresult = $this->getSel('page_main',1);
                        if(!empty($getresult)){
                            $getresult['page_main'] = 0;
                            $this->editSel('id',$getresult['id'],$getresult);
                            $data[$f] = $this->input->post("page_main");
                        }
                        else{$data[$f] = $this->input->post("page_main");}
                    }
                    else{$data[$f] = $this->input->post("page_main");}

                }
                elseif($f == 'call_listworks'){
                    $arr = $this->input->post($f);
                    $data[$f] = $this->implodes($arr);
                }
				else{
                    $data[$f] = $this->input->post($f);
                }
            }
			$this->db->where($this->idkey, $id);
           	$this->db->update($this->table, $data);
            return 'success';
        } else {
            return false;
        }
    }
 
    /**
     * Функция удаления записи из БД по id
     */
    function del($id)
    {
        $this->db->where($this->idkey, $id);
        $this->db->delete($this->table);
    }
 
    /**
     * Получение данных по ID
     */
    function get($id, $where_arr = array())
    {
        if($id!==''){
            $this->db->where($this->idkey, $id);
            $query = $this->db->get($this->table);
        }
        else{
            $query = $this->db->get_where($this->table, $where_arr);
        }
        $rez = $query->row_array();
        return $rez;
    }
	
	/**
     * Получение данных о текущем пользователе
     */
    function getuserinfo($user)
    {
        $this->db->where($this->user, $user);
        $query = $this->db->get($this->table);
        $rez = $query->row_array();
        return $rez;
    }
 
    /**
     * Получение всего списка
     */
    function getlist($start_from = false, $limit = false, $order_by = false, $desc = '', $where = array())
    {
	    if(! $order_by) $order_by = $this->idkey;
        $query = $this->db->order_by($order_by, $desc);
        $query = $this->db->get_where($this->table, $where, $limit, $start_from);
        if($query->num_rows == 0){return array();}
            $rez = $query->result_array();
            for ($i=0;$i<count($rez);$i++) {
                $one = $rez[$i];
                foreach ($one as $key => $value) {
                    if ($key == 'comfort') {
                        $rez[$i][$key] = $this->explodes($value);
                    }
                    elseif($key == 'photos'){
                        $rez[$i][$key] = $this->explodes($value);
                    }
                }
            }
        return $rez;
    }
 

    /**
     *  Преобразовывает строку в массив
     */
    function explodes($str) {
    	$rez = explode(' | ',$str);    	
    	return $rez;
    }
	
	 /**
     *  Преобразовывает строку в массив
     */
    function implodes($arr) {
        if(!empty($arr)){
            $rez = implode(' | ',$arr);
            return $rez;
        }
        else{return '';}
    }
	
   
	/**
	 * Функция удаления пользователя
	 */ 
	function deluser()
	{
		$this->form_validation->set_rules($this->del_rules_user);
		if($this->form_validation->run()){
			$data = array();
			foreach($this->del_rules_user as $one){
				$f = $one['field'];
				$userid = $this->input->post($f);
				foreach($userid as $key=>$value){
					$this->db->where($this->idkey,$key);
					$this->db->delete($this->table);
				}
			}
		}
		else{
			return false;
		}
	}

    /**
     * Функция для редактирования по заданому полю
     */
    function editSel($sel,$id,$data)
    {
        foreach($data as $k=>$v){
            $data[$k] = $v;
        }
        $this->db->where($sel, $id);
        if($this->db->update($this->table, $data)){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Функция для вывода данных из БД по выбранному полю с условием (одно поле)
     */
    function getSel($col,$value)
    {
        $this->db->where($col, $value);
        $query = $this->db->get($this->table);
        $rez = $query->row_array();
        if(!empty($rez)){
            return $rez;
        }
        else{return false;}
    }

    /**
     * Функция для вывода данных из БД по выбранному полю с условием (все поля)
     *
     */
    function getSelAll($col,$value)
    {
        $query = $this->db->query("SELECT * FROM ".'cm_'.$this->table." WHERE ".$col."='".$value."'");
        if($query->num_rows() !== 0){
            return $query->result_array();
        }
        else{return false;}
    }

    /**
     * Удаление записи из БД по выбранному полю
     */
    function delSel($col,$value)
    {
        $this->db->where($col, $value);
        $this->db->delete($this->table);
    }
    //Получение количества строк в выбраной таблице
    function numstr($sel='',$id='id')
    {
        if($sel == ''){
            return $this->db->count_all($this->table);
        }
        else{
            $this->db->like($sel, $id);
            return $this->db->count_all_results($this->table);
        }
    }
    function add_csv($data)
    {
        $this->db->insert($this->table,$data);
    }
    function adds_csv($data)
    {
        $this->db->insert($this->table,$data);
    }
}
?>