<?php
/**
 * Config-file for navigation bar.
 *
 */
return [

    // Use for styling the menu
    'class' => 'navbar',
    'role' => 'custom-dropdown',
    'in_wrapper' => '<input type="checkbox" id="button"><label for="button" onclick></label>',
 
    // Here comes the menu strcture
    'items' => [

        // This is a menu item
        'home'  => [
            'text'  => 'Frågor',
            'url'   => $this->di->get('url')->create('questions'),
            'title' => 'Visa alla frågor'
        ],
 
        // This is a menu item
        'test'  => [
            'text'  => 'Taggar',
            'url'   => $this->di->get('url')->create('tags'),
            'title' => 'Visa taggar',
        ],
 
        // This is a menu item
        'about' => [
            'text'  =>'Användare',
            'url'   => $this->di->get('url')->create('users'),
            'title' => 'Visa användare'
        ],
    ],
 


    /**
     * Callback tracing the current selected menu item base on scriptname
     *
     */
    'callback' => function ($url) {
        if ($this->di->get('request')->getCurrentUrl($url) == $this->di->get('url')->create($url)) {
            return true;
        }
    },



    /**
     * Callback to check if current page is a decendant of the menuitem, this check applies for those
     * menuitems that has the setting 'mark-if-parent' set to true.
     *
     */
    'is_parent' => function ($parent) {
        $route = $this->di->get('request')->getRoute();
        return !substr_compare($parent, $route, 0, strlen($parent));
    },



   /**
     * Callback to create the url, if needed, else comment out.
     *
     */
   /*
    'create_url' => function ($url) {
        return $this->di->get('url')->create($url);
    },
    */
];
