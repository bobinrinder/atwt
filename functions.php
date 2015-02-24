<?php

// include ACF
define( 'ACF_LITE', true );
include_once('plugins/advanced-custom-fields/acf.php');

// define ACF Content
if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_location-details',
		'title' => 'Location Details',
		'fields' => array (
			array (
				'key' => 'field_52faec48634bc',
				'label' => 'City',
				'name' => 'city',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 100,
			),
			array (
				'key' => 'field_52faec8a634bd',
				'label' => 'Country',
				'name' => 'country',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 100,
			),
			array (
				'key' => 'field_52faf89894c06',
				'label' => 'Longitude',
				'name' => 'longitude',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_52faf91694c07',
				'label' => 'Latitude',
				'name' => 'latitude',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 50,
			),
			array (
				'key' => 'field_52faf94494c08',
				'label' => 'Arrival Date',
				'name' => 'arrival_date',
				'type' => 'date_picker',
				'required' => 1,
				'date_format' => 'ddmmyy',
				'display_format' => 'dd/mm/yy',
				'first_day' => 1,
			),
			array (
				'key' => 'field_52faf9ab94c09',
				'label' => 'Departure Date',
				'name' => 'departure_date',
				'type' => 'date_picker',
				'date_format' => 'ddmmyy',
				'display_format' => 'dd/mm/yy',
				'first_day' => 1,
			),
			array (
				'key' => 'field_52faf9e794c0a',
				'label' => 'Accomodation Name',
				'name' => 'accomodation_name',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 150,
			),
			array (
				'key' => 'field_52fafa0194c0b',
				'label' => 'Accomodation Link',
				'name' => 'accomodation_link',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '250',
			),
			array (
				'key' => 'field_52fafa0194c0c',
				'label' => 'Photo Link',
				'name' => 'photo_link',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '250',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'acf_after_title',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}

require_once ( get_stylesheet_directory() . '/options.php' );
?>