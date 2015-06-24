<?php
/*
Copyright 2011  Jani Virta <jani.virta@iqit.fi>
Copyright 2012  Mikko Keskinen <keso@iki.fi>

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License as
published by the Free Software Foundation; either version 2 of
the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class ControllerPaymentEmaksut extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/emaksut');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('emaksut', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$data['redirect'] = $this->url->link('payment', 'token=' . $this->session->data['token'], 'SSL');
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_successful'] = $this->language->get('text_successful');
		$data['text_declined'] = $this->language->get('text_declined');
		$data['text_off'] = $this->language->get('text_off');

		$data['entry_sellerid'] = $this->language->get('entry_sellerid');
		$data['entry_sellerkey'] = $this->language->get('entry_sellerkey');
		$data['entry_sellerkeyver'] = $this->language->get('entry_sellerkeyver');

		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_order_status_delayed'] = $this->language->get('entry_order_status_delayed');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['sellerid'])) {
			$data['error_sellerid'] = $this->error['sellerid'];
		} else {
			$data['error_sellerid'] = '';
		}

		if (isset($this->error['sellerkey'])) {
			$data['error_sellerkey'] = $this->error['sellerkey'];
		} else {
			$data['error_sellerkey'] = '';
		}

		if (isset($this->error['sellerkeyver'])) {
			$data['error_sellerkeyver'] = $this->error['sellerkeyver'];
		} else {
			$data['error_sellerkeyver'] = '';
		}

		$this->document->breadcrumbs = array();

		$this->document->breadcrumbs[] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);

		$this->document->breadcrumbs[] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'],
			'text'      => $this->language->get('text_payment'),
			'separator' => ' :: '
		);

		$this->document->breadcrumbs[] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=payment/emaksut&token=' . $this->session->data['token'],
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);

		$data['action'] = HTTPS_SERVER . 'index.php?route=payment/emaksut&token=' . $this->session->data['token'];

		$data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'];

		if (isset($this->request->post['emaksut_sellerid'])) {
			$data['emaksut_sellerid'] = $this->request->post['emaksut_sellerid'];
		} else {
			$data['emaksut_sellerid'] = $this->config->get('emaksut_sellerid');
		}

		if (isset($this->request->post['emaksut_sellerkey'])) {
			$data['emaksut_sellerkey'] = $this->request->post['emaksut_sellerkey'];
		} else {
			$data['emaksut_sellerkey'] = $this->config->get('emaksut_sellerkey');
		}

		if (isset($this->request->post['emaksut_sellerkeyver'])) {
			$data['emaksut_sellerkeyver'] = $this->request->post['emaksut_sellerkeyver'];
		} else {
			$data['emaksut_sellerkeyver'] = $this->config->get('emaksut_sellerkeyver');
		}

		if (isset($this->request->post['emaksut_test'])) {
			$data['emaksut_test'] = $this->request->post['emaksut_test'];
		} else {
			$data['emaksut_test'] = $this->config->get('emaksut_test');
		}

		if (isset($this->request->post['emaksut_order_status_id'])) {
			$data['emaksut_order_status_id'] = $this->request->post['emaksut_order_status_id'];
		} else {
			$data['emaksut_order_status_id'] = $this->config->get('emaksut_order_status_id');
		}

		if (isset($this->request->post['emaksut_order_status_delayed_id'])) {
			$data['emaksut_order_status_delayed_id'] = $this->request->post['emaksut_order_status_delayed_id'];
		} else {
			$data['emaksut_order_status_delayed_id'] = $this->config->get('emaksut_order_status_delayed_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['emaksut_geo_zone_id'])) {
			$data['emaksut_geo_zone_id'] = $this->request->post['emaksut_geo_zone_id'];
		} else {
			$data['emaksut_geo_zone_id'] = $this->config->get('emaksut_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['emaksut_status'])) {
			$data['emaksut_status'] = $this->request->post['emaksut_status'];
		} else {
			$data['emaksut_status'] = $this->config->get('emaksut_status');
		}

		if (isset($this->request->post['emaksut_sort_order'])) {
			$data['emaksut_sort_order'] = $this->request->post['emaksut_sort_order'];
		} else {
			$data['emaksut_sort_order'] = $this->config->get('emaksut_sort_order');
		}

		$this->id       = 'content';

		$data['heading_title'] = $this->language->get('heading_title');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('payment/emaksut.tpl', $data));

	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/emaksut')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['emaksut_sellerid']) {
			$this->error['sellerid'] = $this->language->get('error_sellerid');
		}

		if (!$this->request->post['emaksut_sellerkey']) {
			$this->error['sellerkey'] = $this->language->get('error_sellerkey');
		}

		if (!$this->request->post['emaksut_sellerkeyver']) {
			$this->error['sellerkeyver'] = $this->language->get('error_sellerkeyver');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}

