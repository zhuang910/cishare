<?php
return array (
		'serverfee' => 0,
		'Declaration' => ( object ) array (
				'name' => 'Declaration',
				'type' => 0,
				'pages' => ( object ) array (
						( object ) array (
								'name' => '',
								'type' => 0,
								'items' => ( object ) array (
										( object ) array (
												'formTitle' => '',
												'formType' => 5,
												'formID' => 'isDeclaration',
												"isInput" => "Y",
												'options' => ( object ) array (
														( object ) array (
																'itemTitle' => 'All information and materials given in this form are true and correct. During my stay in China, I shall abide the laws and decrees of the Chinese government, and will not participate in any activities in China which are deemed to be adverse to the social order of China and are inappropriate to the capacity as a student.',
																'formValue' => 'isDeclaration' 
														) 
												) 
										)
								)
						) 
				) 
		),
		'IsinChina' => ( object ) array (
				'name' => 'IS in China',
				'type' => 0,
				'pages' => ( object ) array (
						( object ) array (
								'name' => '',
								'type' => 0,
								'items' => ( object ) array (
										( object ) array (
												'formTitle' => '',
												'formType' => 6,
												'formID' => 'is_in_china',
												"isInput" => "N",
												'options' => ( object ) array (
														( object ) array (
																'itemTitle' => 'IS in China.',
																'formValue' => 'isinChina' 
														) ,
                                                        ( object ) array (
                                                            'itemTitle' => 'Not in China.',
                                                            'formValue' => 'notinChina'
                                                        )
                                                )
										) ,
                                        ( object ) array (
                                            "formTitle" => "Present school",
                                            "formType" => "1",
                                            "formID" => "is_school_name",
                                            "formHelp" => "Present school",
                                            "isInput" => "Y"
                                        )
								) 
						) 
				) 
		),
		'Mailing_Address' => ( object ) array (
				'name' => 'Mailing Address',
				'type' => 0,
				'pages' => ( object ) array (
						( object ) array (
								'name' => 'Mailing Address',
								'type' => 0,
								'items' => ( object ) array (
										( object ) array (
												"formTitle" => "Receiving way",
												"formType" => "4",
												"formID" => "Receiving_way",
												"formHelp" => "Receiving way. ",
												"isInput" => "Y" ,
                                                'options' => ( object ) array (
                                                    ( object ) array (
                                                        'itemTitle' => 'Take their own',
                                                        'formValue' => 'take_own'
                                                    ),
                                                    ( object ) array (
                                                        'itemTitle' => 'Send by post',
                                                        'formValue' => 'send_post'
                                                    )
                                                )
                                        ),
										( object ) array (
												"formTitle" => "City",
												"formType" => "1",
												"formID" => "mailing_city",
												"formHelp" => "City of residence ",
												"isInput" => "N"
										),
										( object ) array (
												"formTitle" => "Country",
												"formType" => "6",
												"phpArrar" => "global_country",
												"formID" => "mailing_country",
												"formHelp" => "Country of residence",
												"isInput" => "N"
										),
										( object ) array (
												"formTitle" => "Detailed address",
												"formType" => "1",
												"formID" => "Detailed_address",
												"formHelp" => "Detailed address",
												"isInput" => "N"
										)
								) 
						) 
				) 
		) 
);