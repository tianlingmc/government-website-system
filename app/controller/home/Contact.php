<?php
namespace app\controller\home;

use core\Controller;
use app\model\Settings;

/**
 * 联系我们控制器
 */
class Contact extends Controller {
    
    /**
     * 联系页面
     */
    public function index() {
        $settingsModel = new Settings();
        $contact = [
            'address' => $settingsModel->getSetting('contact_address', ''),
            'phone' => $settingsModel->getSetting('contact_phone', ''),
            'email' => $settingsModel->getSetting('contact_email', ''),
            'work_time' => $settingsModel->getSetting('contact_work_time', ''),
            'map' => $settingsModel->getSetting('contact_map', '')
        ];
        
        $this->assign('contact', $contact);
        $this->fetch('contact/index');
    }
}
