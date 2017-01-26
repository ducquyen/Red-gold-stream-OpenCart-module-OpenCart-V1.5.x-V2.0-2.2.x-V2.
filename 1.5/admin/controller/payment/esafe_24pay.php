<?php

class ControllerPaymentEsafe24Pay extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('payment/esafe_24pay');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');
        $this->load->model('localisation/language');
        $this->load->model('localisation/geo_zone');
        $this->load->model('localisation/order_status');

        $languages = $this->model_localisation_language->getLanguages();

        $this->data['languages'] = $languages;
        $this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
        $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $this->model_setting_setting->editSetting('esafe_24pay', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['entry_bank'] = $this->language->get('entry_bank');
        $this->data['entry_order_status'] = $this->language->get('entry_order_status');
        $this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $this->data['entry_order_status'] = $this->language->get('entry_order_status');
        $this->data['entry_order_finish_status'] = $this->language->get('entry_order_finish_status');
        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');
        $this->data['tab_general'] = $this->language->get('tab_general');
        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_all_zones'] = $this->language->get('text_all_zones');


        $this->data['entry_test_mode'] = $this->language->get('entry_test_mode');
        $this->data['entry_test_mode_yes'] = $this->language->get('entry_test_mode_yes');
        $this->data['entry_test_mode_no'] = $this->language->get('entry_test_mode_no');
        $this->data['entry_storeid'] = $this->language->get('entry_storeid');
        $this->data['entry_password'] = $this->language->get('entry_password');
        $this->data['entry_maxdays'] = $this->language->get('entry_maxdays');
        $this->data['entry_maxdays_tip'] = $this->language->get('entry_maxdays_tip');
        $this->data['entry_productname'] = $this->language->get('entry_productname');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
        if (isset($this->error['warning2'])) {
            $this->data['error_warning2'] = $this->error['warning2'];
        } else {
            $this->data['error_warning2'] = '';
        }
        if (isset($this->error['warning3'])) {
            $this->data['error_warning3'] = $this->error['warning3'];
        } else {
            $this->data['error_warning3'] = '';
        }
        if (isset($this->error['warning4'])) {
            $this->data['error_warning4'] = $this->error['warning4'];
        } else {
            $this->data['error_warning4'] = '';
        }
        if (isset($this->error['warning5'])) {
            $this->data['error_warning5'] = $this->error['warning5'];
        } else {
            $this->data['error_warning5'] = '';
        }

        foreach ($languages as $language) {
            if (isset($this->error['bank_' . $language['language_id']])) {
                $this->data['error_bank_' . $language['language_id']] = $this->error['bank_' . $language['language_id']];
            } else {
                $this->data['error_bank_' . $language['language_id']] = '';
            }
        }

        $this->data['breadcrumbs'] = array();
        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('payment/esafe_24pay', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['action'] = $this->url->link('payment/esafe_24pay', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

        foreach ($languages as $language) {
            if (isset($this->request->post['esafe_24pay_description_' . $language['language_id']])) {
                $this->data['esafe_24pay_description_' . $language['language_id']] = $this->request->post['esafe_24pay_description_' . $language['language_id']];
            } else {
                $this->data['esafe_24pay_description_' . $language['language_id']] = $this->config->get('esafe_24pay_description_' . $language['language_id']);
            }
        }

        if (isset($this->request->post['esafe_24pay_order_status_id'])) {
            $this->data['esafe_24pay_order_status_id'] = $this->request->post['esafe_24pay_order_status_id'];
        } else {
            $this->data['esafe_24pay_order_status_id'] = $this->config->get('esafe_24pay_order_status_id');
        }
        if (isset($this->request->post['esafe_24pay_order_finish_status_id'])) {
            $this->data['esafe_24pay_order_finish_status_id'] = $this->request->post['esafe_24pay_order_finish_status_id'];
        } else {
            $this->data['esafe_24pay_order_finish_status_id'] = $this->config->get('esafe_24pay_order_finish_status_id');
        }

        if (isset($this->request->post['esafe_24pay_geo_zone_id'])) {
            $this->data['esafe_24pay_geo_zone_id'] = $this->request->post['esafe_24pay_geo_zone_id'];
        } else {
            $this->data['esafe_24pay_geo_zone_id'] = $this->config->get('esafe_24pay_geo_zone_id');
        }

        if (isset($this->request->post['esafe_24pay_status'])) {
            $this->data['esafe_24pay_status'] = $this->request->post['esafe_24pay_status'];
        } else {
            $this->data['esafe_24pay_status'] = $this->config->get('esafe_24pay_status');
        }

        if (isset($this->request->post['esafe_24pay_sort_order'])) {
            $this->data['esafe_24pay_sort_order'] = $this->request->post['esafe_24pay_sort_order'];
        } else {
            $this->data['esafe_24pay_sort_order'] = $this->config->get('esafe_24pay_sort_order');
        }


        if (isset($this->request->post['esafe_24pay_test_mode'])) {
            $this->data['esafe_24pay_test_mode'] = $this->request->post['esafe_24pay_test_mode'];
        } else {
            $this->data['esafe_24pay_test_mode'] = $this->config->get('esafe_24pay_test_mode');
        }
        if (isset($this->request->post['esafe_24pay_storeid'])) {
            $this->data['esafe_24pay_storeid'] = $this->request->post['esafe_24pay_storeid'];
        } else {
            $this->data['esafe_24pay_storeid'] = $this->config->get('esafe_24pay_storeid');
        }
        if (isset($this->request->post['esafe_24pay_password'])) {
            $this->data['esafe_24pay_password'] = $this->request->post['esafe_24pay_password'];
        } else {
            $this->data['esafe_24pay_password'] = $this->config->get('esafe_24pay_password');
        }
        if (isset($this->request->post['esafe_24pay_maxdays'])) {
            $this->data['esafe_24pay_maxdays'] = $this->request->post['esafe_24pay_maxdays'];
        } else {
            $this->data['esafe_24pay_maxdays'] = $this->config->get('esafe_24pay_maxdays');
        }
        if (isset($this->request->post['esafe_24pay_productname'])) {
            $this->data['esafe_24pay_productname'] = $this->request->post['esafe_24pay_productname'];
        } else {
            $this->data['esafe_24pay_productname'] = $this->config->get('esafe_24pay_productname');
        }

        $this->template = 'payment/esafe_24pay.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
        );

        $this->response->setOutput($this->render());
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'payment/esafe_24pay')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['esafe_24pay_storeid']) {
            $this->error['warning2'] = $this->language->get('error_storeid');
        }

        if (!$this->request->post['esafe_24pay_password']) {
            $this->error['warning3'] = $this->language->get('error_password');
        }

        if (!$this->request->post['esafe_24pay_maxdays']) {
            $this->error['warning4'] = $this->language->get('error_maxdays');
        }
        if (!$this->request->post['esafe_24pay_productname']) {
            $this->error['warning5'] = $this->language->get('error_productname');
        }
        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
?>

