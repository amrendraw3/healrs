<?php

/*-----------------------------------------------------------------------------------*/
/*	Button Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['button'] = array(
	'no_preview' => true,
	'params' => array(
		'url' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Button URL', 'kleo_framework'),
			'desc' => __('Add the button\'s url eg http://example.com', 'kleo_framework')
		),
		'style' => array(
			'type' => 'select',
			'label' => __('Button Style', 'kleo_framework'),
			'desc' => __('Select the button\'s style, ie the button\'s colour', 'kleo_framework'),
			'options' => array(
				'standard' => 'Primary color',
				'secondary' => 'Secondary color',
				'success' => 'Green',
				'alert' => 'Red'
			)
		),
		'size' => array(
			'type' => 'select',
			'label' => __('Button Size', 'kleo_framework'),
			'desc' => __('Select the button\'s size', 'kleo_framework'),
			'options' => array(
				'standard' => 'Standard',
                                'large' => 'Large',
                                'medium' => 'Medium',
				'small' => 'Small',
				'tiny' => 'Tiny'
			)
		),
		'round' => array(
            'std' => '0',
			'type' => 'select',
			'label' => __('Rounded corners', 'kleo_framework'),
			'desc' => __('Check if you want the button to have rounded corners', 'kleo_framework'),
			'options' => array(
                '0' => 'No',
				'radius' => 'A bit Rounded',
                'round' => 'Rounded'
			)

		),
                'icon' => array(
                    'std' => '',
                    'type' => 'select',
                    'label' => __('Icon', 'kleo_framework'),
                    'desc' => __('Select an icon to display inside button. View all icons <a target="_blank" href="http://fortawesome.github.io/Font-Awesome/cheatsheet/">here</a>', 'kleo_framework'),
                    'options' => awesome_array()
                ),
		'icon_position' => array(
                        'std' => 'before',
			'type' => 'select',
			'label' => __('Icon position', 'kleo_framework'),
			'desc' => __('Select icon position', 'kleo_framework'),
			'options' => array(
				'before' => 'Before text',
                                'after' => 'After text'
			)
                ),
		'target' => array(
			'type' => 'select',
			'label' => __('Button Target', 'kleo_framework'),
			'desc' => __('_self = open in same window. _blank = open in new window', 'kleo_framework'),
			'options' => array(
				'_self' => '_self',
				'_blank' => '_blank'
			)
		),
		'content' => array(
			'std' => 'Button Text',
			'type' => 'text',
			'label' => __('Button\'s Text', 'kleo_framework'),
			'desc' => __('Add the button\'s text', 'kleo_framework'),
		)
	),
	'shortcode' => '[kleo_button url="{{url}}" style="{{style}}" size="{{size}}" round="{{round}}" icon="{{icon}},{{icon_position}}" target="{{target}}"] {{content}} [/kleo_button]',
	'popup_title' => __('Insert Button Shortcode', 'kleo_framework')
);

/*-----------------------------------------------------------------------------------*/
/*	Alert Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['alert'] = array(
	'no_preview' => true,
	'params' => array(
		'style' => array(
			'type' => 'select',
			'label' => __('Alert Style', 'kleo_framework'),
			'desc' => __('Select the alert\'s style, ie the alert colour', 'kleo_framework'),
			'options' => array(
				'standard' => 'Standard',
                                'success' => 'Green',
                                'alert' => 'Red',
				'secondary' => 'Secondary',
				
			)
		),
		'content' => array(
			'std' => 'Your Alert!',
			'type' => 'textarea',
			'label' => __('Alert Text', 'kleo_framework'),
			'desc' => __('Add the alert\'s text', 'kleo_framework'),
		)
            
		
	),
	'shortcode' => '[kleo_alert style="{{style}}"] {{content}} [/kleo_alert]',
	'popup_title' => __('Insert Alert Shortcode', 'kleo_framework')
);

/*-----------------------------------------------------------------------------------*/
/*	Toggle Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['toggle'] = array(
	'no_preview' => true,
	'params' => array(
		'title' => array(
			'type' => 'text',
			'label' => __('Toggle Content Title', 'kleo_framework'),
			'desc' => __('Add the title that will go above the toggle content', 'kleo_framework'),
			'std' => 'Title'
		),
		'content' => array(
			'std' => 'Content',
			'type' => 'textarea',
			'label' => __('Toggle Content', 'kleo_framework'),
			'desc' => __('Add the toggle content. Will accept HTML', 'kleo_framework'),
		),
		'opened' => array(
			'type' => 'select',
			'label' => __('Toggle State', 'kleo_framework'),
			'desc' => __('Select the state of the toggle on page load', 'kleo_framework'),
			'options' => array(
				'yes' => 'Opened',
				'no' => 'Closed'
			)
		),
		
	),
	'shortcode' => '[kleo_toggle title="{{title}}" opened="{{opened}}"] {{content}} [/kleo_toggle]',
	'popup_title' => __('Insert Toggle Content Shortcode', 'kleo_framework')
);

/*-----------------------------------------------------------------------------------*/
/*	Tabs Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['tabs'] = array(
    'params' => array(),
    'no_preview' => true,
    'shortcode' => '[kleo_tabs centered="{{centered}}"] {{child_shortcode}}  [/kleo_tabs]',
    'popup_title' => __('Insert Tab Shortcode', 'kleo_framework'),
	'params' => array(
		'centered' => array(
			'type' => 'select',
			'label' => __('Centered tabs', 'kleo_framework'),
			'desc' => __('Enable centered tabs title', 'kleo_framework'),
			'options' => array(
				'' => 'No',
				'yes' => 'Yes'
			)
		),
		
	),
    'child_shortcode' => array(
        'params' => array(
            'title' => array(
                'std' => 'Title',
                'type' => 'text',
                'label' => __('Tab Title', 'kleo_framework'),
                'desc' => __('Title of the tab', 'kleo_framework'),
            ),
            'content' => array(
                'std' => 'Tab Content',
                'type' => 'textarea',
                'label' => __('Tab Content', 'kleo_framework'),
                'desc' => __('Add the tabs content', 'kleo_framework')
            ),
            'active' => array(
                'std' => 'Active',
                'type' => 'radio',
                'name' => 'activetab',
                'label' => __('Active', 'kleo_framework'),
                'desc' => __('If the tab should be active by default', 'kleo_framework'),
            ),
            
            
        ),
        'shortcode' => '[kleo_tab title="{{title}}" active={{active}}] {{content}} [/kleo_tab]',
        'clone_button' => __('Add Tab', 'kleo_framework')
    )
);


/*-----------------------------------------------------------------------------------*/
/*	Row Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['row'] = array(

	'popup_title' => __('Insert Row Shortcode', 'kleo_framework'),
	'no_preview' => true,
        'params' => array(
                'content' => array(
                        'std' => '',
                        'type' => 'textarea',
                        'label' => __('Row Content', 'kleo_framework'),
                        'desc' => __('Add the row content.', 'kleo_framework'),
                )
        ),
        'shortcode' => '[kleo_row] {{content}} [/kleo_row]'
);


/*-----------------------------------------------------------------------------------*/
/*	Columns Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['columns'] = array(
	'params' => array(),
	'shortcode' => ' {{child_shortcode}} ', // as there is no wrapper shortcode
	'popup_title' => __('Insert Columns Shortcode', 'kleo_framework'),
	'no_preview' => true,
	
	// child shortcode is clonable & sortable
	'child_shortcode' => array(
		'params' => array(
			'column' => array(
				'type' => 'select',
				'label' => __('Column Type', 'kleo_framework'),
				'desc' => __('Select the type, ie width of the column.', 'kleo_framework'),
				'options' => array(
                                    'kleo_one_third' => 'One Third',
                                    'kleo_two_third' => 'Two Thirds',
                                    'kleo_one_fourth' => 'One Fourth',
                                    'kleo_three_fourth' => 'Three Fourth',
                                    'kleo_one_half' => 'One Half',
                                    'kleo_one' => 'One',
				)
			),
			'content' => array(
				'std' => '',
				'type' => 'textarea',
				'label' => __('Column Content', 'kleo_framework'),
				'desc' => __('Add the column content.', 'kleo_framework'),
			)
		),
		'shortcode' => '[{{column}}] {{content}} [/{{column}}] ',
		'clone_button' => __('Add Column', 'kleo_framework')
	)
);



/*-----------------------------------------------------------------------------------*/
/*	Section
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['section'] = array(
	'no_preview' => true,
	'params' => array(

		'bg' => array(
            'std' => '',
			'type' => 'text',
			'label' => __('Background', 'kleo_framework'),
			'desc' => __('<input rel-id="kleo_bg" type="button" class="sc_upload_image_button" value="Upload"> <br>Display a background for this section', 'kleo_framework'),

		),
		'centered' => array(
            'std' => '0',
			'type' => 'checkbox',
			'label' => __('Centered text', 'kleo_framework'),
			'desc' => __('Check if you want to have centered text', 'kleo_framework'),

		),
		'border' => array(
            'std' => '0',
			'type' => 'checkbox',
			'label' => __('Show Border', 'kleo_framework'),
			'desc' => __('Add a bottom border to the section', 'kleo_framework'),

		),

		'content' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __('Content', 'kleo_framework'),
			'desc' => __('Section content', 'kleo_framework'),
		)
	),
	'shortcode' => '[kleo_section bg="{{bg}}" centered={{centered}} border={{border}}]{{content}}[/kleo_section]',
	'popup_title' => __('Insert Section Shortcode', 'kleo_framework')
);


/*-----------------------------------------------------------------------------------*/
/*	Panel Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['panel'] = array(
	'no_preview' => true,
	'params' => array(

		'style' => array(
			'type' => 'select',
			'label' => __('Panel Style', 'kleo_framework'),
			'desc' => '',
			'options' => array(
				'standard' => 'Standard',
				'callout' => 'Callout',
			)
		),
		'round' => array(
                        'std' => '',
			'type' => 'checkbox',
			'label' => __('Rounded corners', 'kleo_framework'),
			'desc' => __('Check if you want the panel to have rounded corners', 'kleo_framework'),

		),
		'content' => array(
			'std' => 'Panel content',
			'type' => 'textarea',
			'label' => __('Panel content', 'kleo_framework'),
			'desc' => __('Add the panel\'s content', 'kleo_framework'),
		)
	),
	'shortcode' => '[kleo_panel style="{{style}}" round="{{round}}"] {{content}} [/kleo_panel]',
	'popup_title' => __('Insert Panel Shortcode', 'kleo_framework')
);


/*-----------------------------------------------------------------------------------*/
/*	Pricing table Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['pricing_table'] = array(
    'params' => array(
        'title' => array(
            'std' => 'Title',
            'type' => 'text',
            'label' => __('Title', 'kleo_framework'),
            'desc' => '',
        ),
        'price' => array(
            'std' => 'Price',
            'type' => 'text',
            'label' => __('Price', 'kleo_framework'),
            'desc' => '',
        ),
        'description' => array(
            'std' => 'Description',
            'type' => 'textarea',
            'label' => __('Description', 'kleo_framework'),
            'desc' => ''
        )
        
        
    ),
    'no_preview' => true,
    'shortcode' => '[kleo_pricing_table title="{{title}}" price="{{price}}" description="{{description}}"] {{child_shortcode}}  [/kleo_pricing_table]',
    'popup_title' => __('Insert Pricing table Shortcode', 'kleo_framework'),
    
    'child_shortcode' => array(
        'params' => array(

            'content' => array(
                'std' => 'Item',
                'type' => 'textarea',
                'label' => __('Item', 'kleo_framework'),
                'desc' => ''
            )
            
        ),
        'shortcode' => '[kleo_pricing_item] {{content}} [/kleo_pricing_item]',
        'clone_button' => __('Add Item', 'kleo_framework')
    )
);


/*-----------------------------------------------------------------------------------*/
/*	Progress bar
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['progress_bar'] = array(
	'no_preview' => true,
	'params' => array(
		'style' => array(
			'type' => 'select',
			'label' => __('Bar Style', 'kleo_framework'),
			'desc' => __('Select the bar\'s colour', 'kleo_framework'),
			'options' => array(
				'standard' => 'Primary color',
				'secondary' => 'Secondary color',
				'success' => 'Green',
				'alert' => 'Red'
			)
		),
		'round' => array(
                        'std' => '',
			'type' => 'checkbox',
			'label' => __('Rounded corners', 'kleo_framework'),
			'desc' => __('Check if you want the button to have rounded corners', 'kleo_framework'),

		),

		'width' => array(
			'std' => '50',
			'type' => 'text',
			'label' => __('Completed percentage', 'kleo_framework'),
			'desc' => __('Set the completed percentage for the progress bar', 'kleo_framework'),
		)
	),
	'shortcode' => '[kleo_progress_bar style="{{style}}" round="{{round}}" width="{{width}}"]',
	'popup_title' => __('Insert Progress bar Shortcode', 'kleo_framework')
);



/*-----------------------------------------------------------------------------------*/
/*	Accordion Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['accordion'] = array(
    'params' => array(
			'opened' => array(
				'std' => '1',
				'type' => 'text',
				'label' => __('Default opened', 'kleo_framework'),
				'desc' => __('Enter the accordion number you want to be opened by default. Enter none if you want to have all closed.', 'kleo_framework'),
			)
		),
    'no_preview' => true,
    'shortcode' => '[kleo_accordion opened="{{opened}}"] {{child_shortcode}}  [/kleo_accordion]',
    'popup_title' => __('Insert Accordion Shortcode', 'kleo_framework'),
    
    'child_shortcode' => array(
        'params' => array(
            'title' => array(
                'std' => 'Title',
                'type' => 'text',
                'label' => __('Accordion Item Title', 'kleo_framework'),
                'desc' => __('Title of the Accordion Item', 'kleo_framework'),
            ),
            'content' => array(
                'std' => 'Accordion Item Content',
                'type' => 'textarea',
                'label' => __('Accordion Item Content', 'kleo_framework'),
                'desc' => __('Add the accordion\'s item content', 'kleo_framework')
            )
            
        ),
        'shortcode' => '[kleo_accordion_item title="{{title}}"] {{content}} [/kleo_accordion_item]',
        'clone_button' => __('Add Accordion Item', 'kleo_framework')
    )
);


/*-----------------------------------------------------------------------------------*/
/*      Colored text Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['colored_text'] = array(
	'no_preview' => true,
	'params' => array(
		'color' => array(
                        'std' => '#F00056',
			'type' => 'text',
			'label' => __('Text Color', 'kleo_framework'),
			'desc' => __('Select the text\'s color', 'kleo_framework'),
	
		),
		'content' => array(
			'std' => 'Your Text!',
			'type' => 'textarea',
			'label' => __('Your text', 'kleo_framework'),
			'desc' => ''
		)
            
		
	),
	'shortcode' => '[kleo_colored_text color="{{color}}"] {{content}} [/kleo_colored_text]',
	'popup_title' => __('Insert Colored Text Shortcode', 'kleo_framework')
);


/*-----------------------------------------------------------------------------------*/
/*	Home - Call to action Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['call_to_action'] = array(
	'no_preview' => true,
	'params' => array(
		'bg' => array(
            'std' => '',
			'type' => 'text',
			'label' => __('Background', 'kleo_framework'),
			'desc' => '<input rel-id="kleo_bg" type="button" class="sc_upload_image_button" value="Upload"> <br>'.__('Change the default background', 'kleo_framework'),

		),
		'content' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __('Content', 'kleo_framework'),
			'desc' => ''
		)
            
		
	),
	'shortcode' => '[kleo_call_to_action bg="{{bg}}"] {{content}} [/kleo_call_to_action]',
	'popup_title' => __('Insert Call to Action Shortcode', 'kleo_framework')
);


/*-----------------------------------------------------------------------------------*/
/*	Lead Paragraph
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['lead_paragraph'] = array(
	'no_preview' => true,
	'params' => array(
		'content' => array(
			'std' => 'Your Text!',
			'type' => 'textarea',
			'label' => __('Your text', 'kleo_framework'),
			'desc' => ''
		)
	),
	'shortcode' => '[kleo_lead_paragraph] {{content}} [/kleo_lead_paragraph]',
	'popup_title' => __('Insert Lead Paragraph Shortcode', 'kleo_framework')
);


/*-----------------------------------------------------------------------------------*/
/*	Headings
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['h1'] = array(
	'no_preview' => true,
	'params' => array(
		'content' => array(
			'std' => 'Your Text!',
			'type' => 'text',
			'label' => __('Your text', 'kleo_framework'),
			'desc' => ''
		)
	),
	'shortcode' => '[kleo_h1] {{content}} [/kleo_h1]',
	'popup_title' => __('Insert H1 Shortcode', 'kleo_framework')
);
$kleo_shortcodes['h2'] = array(
	'no_preview' => true,
	'params' => array(
		'content' => array(
			'std' => 'Your Text!',
			'type' => 'text',
			'label' => __('Your text', 'kleo_framework'),
			'desc' => ''
		)
	),
	'shortcode' => '[kleo_h2] {{content}} [/kleo_h2]',
	'popup_title' => __('Insert H2 Shortcode', 'kleo_framework')
);
$kleo_shortcodes['h3'] = array(
	'no_preview' => true,
	'params' => array(
		'content' => array(
			'std' => 'Your Text!',
			'type' => 'text',
			'label' => __('Your text', 'kleo_framework'),
			'desc' => ''
		)
	),
	'shortcode' => '[kleo_h3] {{content}} [/kleo_h3]',
	'popup_title' => __('Insert H3 Shortcode', 'kleo_framework')
);
$kleo_shortcodes['h4'] = array(
	'no_preview' => true,
	'params' => array(
		'content' => array(
			'std' => 'Your Text!',
			'type' => 'text',
			'label' => __('Your text', 'kleo_framework'),
			'desc' => ''
		)
	),
	'shortcode' => '[kleo_h4] {{content}} [/kleo_h4]',
	'popup_title' => __('Insert H4 Shortcode', 'kleo_framework')
);
$kleo_shortcodes['h5'] = array(
	'no_preview' => true,
	'params' => array(
		'content' => array(
			'std' => 'Your Text!',
			'type' => 'text',
			'label' => __('Your text', 'kleo_framework'),
			'desc' => ''
		)
	),
	'shortcode' => '[kleo_h5] {{content}} [/kleo_h5]',
	'popup_title' => __('Insert H5 Shortcode', 'kleo_framework')
);
$kleo_shortcodes['h6'] = array(
	'no_preview' => true,
	'params' => array(
		'content' => array(
			'std' => 'Your Text!',
			'type' => 'text',
			'label' => __('Your text', 'kleo_framework'),
			'desc' => ''
		)
	),
	'shortcode' => '[kleo_h6] {{content}} [/kleo_h6]',
	'popup_title' => __('Insert H6 Shortcode', 'kleo_framework')
);


/*-----------------------------------------------------------------------------------*/
/*	Video Button Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['button_video'] = array(
	'no_preview' => true,
	'params' => array(
		'url' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Button URL', 'kleo_framework'),
			'desc' => __('Add video\'s embed url that will open in popup eg http://www.youtube.com/embed/FtquI061bag', 'kleo_framework')
		),
		'style' => array(
			'type' => 'select',
			'label' => __('Button Style', 'kleo_framework'),
			'desc' => __('Select the button\'s colour', 'kleo_framework'),
			'options' => array(
				'standard' => 'Primary color',
				'secondary' => 'Secondary color',
				'success' => 'Green',
				'alert' => 'Red'
			)
		),
		'size' => array(
			'type' => 'select',
			'label' => __('Button Size', 'kleo_framework'),
			'desc' => __('Select the button\'s size', 'kleo_framework'),
			'options' => array(
				'standard' => 'Standard',
                                'large' => 'Large',
                                'medium' => 'Medium',
				'small' => 'Small',
				'tiny' => 'Tiny'
			)
		),
		'round' => array(
                        'std' => '0',
			'type' => 'select',
			'label' => __('Rounded corners', 'kleo_framework'),
			'desc' => __('Check if you want the button to have rounded corners', 'kleo_framework'),
			'options' => array(
                                '0' => 'No',
				'radius' => 'A bit Rounded',
                                'round' => 'Rounded'
			)

		),
        'icon' => array(
            'std' => '',
            'type' => 'select',
            'label' => __('Icon', 'kleo_framework'),
            'desc' => __('Select an icon to display inside button. View all icons <a target="_blank" href="http://fortawesome.github.io/Font-Awesome/cheatsheet/">here</a>', 'kleo_framework'),
            'options' => awesome_array()
        ),
		'icon_position' => array(
                        'std' => 'before',
			'type' => 'select',
			'label' => __('Icon position', 'kleo_framework'),
			'desc' => __('Select icon position', 'kleo_framework'),
			'options' => array(
				'before' => 'Before text',
                                'after' => 'After text'
			)
                ),
            
		'content' => array(
			'std' => 'Button Text',
			'type' => 'text',
			'label' => __('Button\'s Text', 'kleo_framework'),
			'desc' => __('Add the button\'s text', 'kleo_framework'),
		)
	),
	'shortcode' => '[kleo_button_video url="{{url}}" style="{{style}}" size="{{size}}" round="{{round}}" icon="{{icon}},{{icon_position}}"] {{content}} [/kleo_button_video]',
	'popup_title' => __('Insert Video Button Shortcode', 'kleo_framework')
);



/*-----------------------------------------------------------------------------------*/
/*	Status icons Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['status_icon'] = array(
    
    'params' => array(),
    'no_preview' => true,
    'shortcode' => '{{child_shortcode}}',
    'popup_title' => __('Insert Status Icons Shortcode', 'kleo_framework'),
    

		'params' => array(
				'type' => array(
					'type' => 'select',
					'label' => __('Type', 'kleo_framework'),
					'desc' => '',
					'options' => array(
							'total' => 'Total members',
							'members_online' => 'Members online',
							'custom' => 'Custom'
					)
				),
				'image' => array(
					'std' => '',
					'type' => 'text',
					'label' => __('Image', 'kleo_framework'),
					'desc' => '<input type="button" class="sc_upload_image_button" value="Upload">',
				),
				'field' => array(
					'std' => '',
					'type' => 'text',
					'label' => __('Field name', 'kleo_framework'),
					'desc' => __('Enter the field name to get members by. Works only when Type is set to custom', 'kleo_framework'),
				),
				'value' => array(
					'std' => '',
					'type' => 'text',
					'label' => __('Field value', 'kleo_framework'),
					'desc' => __('Enter the field value to get members by. Works only when Type is set to custom', 'kleo_framework'),
				),    
				'online' => array(
					'type' => 'select',
					'label' => __('Online', 'kleo_framework'),
					'desc' => __('Get only online members or not. Works only when Type is set to custom', 'kleo_framework'),
					'options' => array(
							'yes' => 'Yes',
							'no' => 'No'
					)
				),
				'subtitle' => array(
					'std' => 'Subtitle Text',
					'type' => 'text',
					'label' => __('Subtitle Text', 'kleo_framework'),
					'desc' => '',
				)
		),
		'shortcode' => '[kleo_status_icon type="{{type}}" image="{{image}}" subtitle="{{subtitle}}" field="{{field}}" value="{{value}}" online="{{online}}"]'

  
);

/*-----------------------------------------------------------------------------------*/
/*	Members online
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['members_online'] = array(
	'no_preview' => true,
	'params' => array(
		'field_name' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Field name', 'kleo_framework'),
			'desc' => 'Field name to filter members by.',
		),
		'field' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Field Value', 'kleo_framework'),
			'desc' => 'The value of the above fields to get the members after.',
		),
	),
	'shortcode' => '[kleo_members_online field_name="{{field_name}} field="{{field}}"]',
	'popup_title' => __('Insert Members online Shortcode', 'kleo_framework')
);


/*-----------------------------------------------------------------------------------*/
/*	Image rounded Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['img_rounded'] = array(
	'no_preview' => true,
	'params' => array(
		'src' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Image', 'kleo_framework'),
			'desc' => '<input type="button" class="sc_upload_image_button" value="Upload">',
		),
	),
	'shortcode' => '[kleo_img_rounded src="{{src}}"]',
	'popup_title' => __('Insert Rounded Image Shortcode', 'kleo_framework')
);



/*-----------------------------------------------------------------------------------*/
/*	Blog Carousel
/*-----------------------------------------------------------------------------------*/
$bc_cats = array();
$bc_cats['all'] = 'All';
foreach (get_categories() as $bcat)
{
    $bc_cats[$bcat->term_id] = $bcat->name;
}

$kleo_shortcodes['posts_carousel'] = array(
	'no_preview' => true,
	'params' => array(
		'cat' => array(
			'type' => 'select',
			'label' => __('Category', 'kleo_framework'),
			'desc' => __('Show posts only from the selected category', 'kleo_framework'),
			'options' => $bc_cats
		),
		'limit' => array(
			'type' => 'text',
			'label' => __('Limit', 'kleo_framework'),
			'desc' => __('Limit the posts in carousel', 'kleo_framework'),
		)
        
	),
	'shortcode' => '[kleo_posts_carousel cat="{{cat}}" limit="{{limit}}"]',
	'popup_title' => __('Insert Posts Carousel Shortcode', 'kleo_framework')
);



/*-----------------------------------------------------------------------------------*/
/*	Blog Articles
/*-----------------------------------------------------------------------------------*/
$bc_cats = array();
$bc_cats['all'] = 'All';
foreach (get_categories() as $bcat)
{
    $bc_cats[$bcat->term_id] = $bcat->name;
}

$kleo_shortcodes['articles'] = array(
    'no_preview' => true,
    'params' => array(
        'display' => array(
            'type' => 'select',
            'label' => __('Display type', 'kleo_framework'),
            'desc' => __('Display in a normal or grid view', 'kleo_framework'),
            'options' => array(
                '' => 'Normal',
                'grid' => 'Grid'
            ),
            'std' => ''
        ),
        'columns' => array(
            'type' => 'select',
            'label' => __('Number of columns', 'kleo_framework'),
            'desc' => __('Applies only for Grid Display style', 'kleo_framework'),
            'options' => array(
                'four' => 'Four',
                'three' => 'Three',
                'two' => 'Two'
            ),
            'std' => 'four'
        ),
        'show_meta' => array(
            'type' => 'select',
            'label' => __('Show post meta', 'kleo_framework'),
            'desc' => '',
            'options' => array(
                'disable' => 'Disable',
                'enable' => 'Enable'
            ),
            'std' => 'disable'
        ),
        'cat' => array(
            'type' => 'select',
            'label' => __('Category', 'kleo_framework'),
            'desc' => __('Show posts only from the selected category', 'kleo_framework'),
            'options' => $bc_cats,
            'std' => ''
        ),
        'post_types' => array(
            'type' => 'text',
            'label' => __('Post types', 'kleo_framework'),
            'desc' => __('Show only certain post types', 'kleo_framework'),
            'std' => 'post'
        ),
        'post_formats' => array(
            'type' => 'text',
            'label' => __('Post Formats', 'kleo_framework'),
            'desc' => __('Show only certain post formats', 'kleo_framework'),
            'std' => 'all'
        ),
        'limit' => array(
            'type' => 'text',
            'label' => __('Limit', 'kleo_framework'),
            'desc' => __('Limit the posts number', 'kleo_framework'),
            'std' => ''
        )

    ),
    'shortcode' => '[kleo_articles display="{{display}}" columns="{{columns}}" cat="{{cat}}" limit="{{limit}}" post_types="{{post_types}}" post_formats="{{post_formats}}" show_meta="{{show_meta}}"]',
    'popup_title' => __('Insert Articles Shortcode', 'kleo_framework')
);



/*-----------------------------------------------------------------------------------*/
/*	Slider Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['slider'] = array(
    'params' => array(),
    'no_preview' => true,
    'shortcode' => '[kleo_slider] {{child_shortcode}}  [/kleo_slider]',
    'popup_title' => __('Insert Slider Shortcode', 'kleo_framework'),
    
    'child_shortcode' => array(
        'params' => array(
            'src' => array(
                'std' => '',
                'type' => 'text',
                'label' => __('Image', 'kleo_framework'),
                'desc' => '<input type="button" class="sc_upload_image_button" value="Upload"> <br>'.__('Select your image', 'kleo_framework'),
            ),
            
        ),
        'shortcode' => '[kleo_slider_image src="{{src}}"]',
        'clone_button' => __('Add Slider Image', 'kleo_framework')
    )
);

/*-----------------------------------------------------------------------------------*/
/* Icon Config
/*-----------------------------------------------------------------------------------*/


$kleo_shortcodes['icon'] = array(
	'no_preview' => true,
	'params' => array(
        'icon' => array(
            'std' => '',
            'type' => 'select',
            'label' => __('Icon', 'kleo_framework'),
            'desc' => __('Select the icon to be displayed. View all icons <a target="_blank" href="http://fortawesome.github.io/Font-Awesome/cheatsheet/">here</a>', 'kleo_framework'),
            'options' => awesome_array()
        ),
        'size' => array(
            'std' => '',
            'type' => 'select',
            'label' => __('Size', 'kleo_framework'),
            'desc' => __('Select the icon size', 'kleo_framework'),
            'options' => array(
                'normal' => 'Normal','large' => 'Large', '2x' => '2x', '3x' => '3x' , '4x' => '4x'
            )
        ),
	),
	'shortcode' => '[kleo_icon icon="{{icon}}" size={{size}}]',
	'popup_title' => __('Insert Icon Shortcode', 'kleo_framework')
);


/*-----------------------------------------------------------------------------------*/
/* Search members
/*-----------------------------------------------------------------------------------*/


$kleo_shortcodes['search_members'] = array(
	'no_preview' => false,
	'params' => array(
		'before' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Before text', 'kleo_framework'),
			'desc' => __('Text that appears before search form', 'kleo_framework')
		),
		'profiles' => array(
            'std' => '1',
			'type' => 'checkbox',
			'label' => __('Show profiles', 'kleo_framework'),
			'desc' => __('Check if you want to show profiles carousel in form footer', 'kleo_framework'),

		),

	),
	'shortcode' => '[kleo_search_members before="{{before}}" profiles={{profiles}}]',
	'popup_title' => __('Insert Seach Form Shortcode', 'kleo_framework')
);

/*-----------------------------------------------------------------------------------*/
/* Search members horizontal
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['search_members_horizontal'] = array(
	'no_preview' => false,
	'params' => array(
		'before' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Before text', 'kleo_framework'),
			'desc' => __('Text that appears before search form', 'kleo_framework')
		),
		'button' => array(
            'std' => '1',
			'type' => 'checkbox',
			'label' => __('Show search button', 'kleo_framework'),
			'desc' => __('Check if you want to show a search button. Otherwise it will automatically refresh on form change', 'kleo_framework'),

		),

	),
	'shortcode' => '[kleo_search_members_horizontal before="{{before}}" button={{button}}]',
	'popup_title' => __('Insert Vertical Seach Form Shortcode', 'kleo_framework')
);

/*-----------------------------------------------------------------------------------*/
/* Register form
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['register_form'] = array(
	'no_preview' => false,
	'params' => array(
        'profiles' => array(
            'std' => '1',
            'type' => 'select',
            'label' => __('Show Profiles', 'kleo_framework'),
            'desc' => __('Display Latest Profiles Carousel below the form', 'kleo_framework'),
            'options' => array(
                '1' => 'Yes','0' => 'No'
            )
        ),
		'title' => array(
			'std' => __("Create an Account", 'kleo_framework'),
			'type' => 'text',
			'label' => __('Title', 'kleo_framework'),
			'desc' => __('Title that appears in the form', 'kleo_framework')
		),
		'details' => array(
			'std' => __("Registering for this site is easy, just fill in the fields below and we will get a new account set up for you in no time.",'kleo_framework'),
			'type' => 'text',
			'label' => __('Details', 'kleo_framework'),
			'desc' => __('Details to show below the title', 'kleo_framework')
		)			
	),
	'shortcode' => '[kleo_register_form profiles={{profiles}} title="{{title}}" details="{{details}}"]',
	'popup_title' => __('Insert Register form Shortcode', 'kleo_framework')
);

/*-----------------------------------------------------------------------------------*/
/* Members carousel
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['members_carousel'] = array(
	'no_preview' => false,
	'params' => array(
        'type' => array(
            'std' => 'newest',
            'type' => 'select',
            'label' => __('Type', 'kleo_framework'),
            'desc' => __('What members to show', 'kleo_framework'),
            'options' => array(
                'newest' => 'Newests','active' => 'Most Active', 'popular' => 'Most Popular'
            )
        ),
		'total' => array(
			'std' => '12',
			'type' => 'text',
			'label' => __('Total', 'kleo_framework'),
			'desc' => __('The number of members to get', 'kleo_framework')
		),
		'width' => array(
			'std' => '94',
			'type' => 'text',
			'label' => __('Image width', 'kleo_framework'),
			'desc' => __('Member avatar size', 'kleo_framework')
		),
		
	),
	'shortcode' => '[kleo_members_carousel type="{{type}}" total={{total}} width={{width}}]',
	'popup_title' => __('Insert Members Carousel Shortcode', 'kleo_framework')
);

/*-----------------------------------------------------------------------------------*/
/* bbPress statistics
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['bbpress_stats'] = array(
	'no_preview' => false,
	'params' => array(
      'type' => array(
          'std' => 'forums',
          'type' => 'select',
          'label' => __('Type', 'kleo_framework'),
          'desc' => __('What statistic to show', 'kleo_framework'),
          'options' => array(
              'forums' => 'Total Forums', 'topics' => 'Total Topics', 'replies' => 'Total Replies'
          )
      ),
		
	),
	'shortcode' => '[kleo_bbpress_stats type="{{type}}"]',
	'popup_title' => __('Insert bbPress Stats Shortcode', 'kleo_framework')
);
?>