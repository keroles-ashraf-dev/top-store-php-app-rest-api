<?php

namespace App\Controllers\Store\Main;

use System\Controller;
use System\View\ViewInterface;

class LayoutController extends Controller
{
  /**
   * Disabled Sections container
   *
   * @var array
   */
  private $disabledSections = [];

  /**
   * Render the layout with the given view Object
   *
   * @param \System\View\ViewInterface $view
   */
  public function render(ViewInterface $view)
  {
    $data['content'] = $view;

    $sections = ['header', 'navbar', 'footer'];

    foreach ($sections as $section) {
      $data[$section] = in_array($section, $this->disabledSections)
        ? ''
        : $this->load->controller('Store/Main/' . ucfirst($section))->index();
    }

    return $this->view->render('store/main/layout', $data);
  }

  /**
   * Determine what will be not displayed in the layout page
   *
   * @param mixed $sections
   * @return $this
   */
  public function disable($sections)
  {
    if (!is_array($sections)) {
      $this->disabledSections[] = $sections;
      return $this;
    }

    foreach ($sections as $section) {
      $this->disabledSections[] = $section;
    }
    return $this;
  }

  /**
   * Set the title for the store page
   *
   * @param string $title
   * @return void
   */
  public function title($title)
  {
    $this->html->setTitle($title . ' | ' . $this->settings->get('site_name'));
  }
}
