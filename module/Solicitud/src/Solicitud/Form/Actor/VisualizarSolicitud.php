<?php
namespace Solicitud\Form\Actor;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory as InputFactory;
use Zend\Db\Adapter\AdapterInterface;


class VisualizarSolicitud extends Form
{

	protected $adapter;
	
	//parámetro del constructor: adaptador de la base de datos
	public function __construct(AdapterInterface $dbadapter, 
								$aprobarEnabled, $vistoBuenoEnabled,
								$pendienteEnabled = TRUE, $anularEnabled = TRUE, $rechazarEnabled = TRUE, 
								$enviarCorreoEnabled = FALSE, $observacionesEnabled = TRUE) 
	{ 
		$this->adapter = $dbadapter; //Asignación de nuestro adaptador de base de datos
		$this->observacionesEnabled = $observacionesEnabled;
		parent::__construct('solicitud');

		$this->setAttribute('method', 'post');

		if ($observacionesEnabled){
			$this->add(array(
					'name' => 'observaciones', // de la tabla solicitudes
					'type' => 'Zend\Form\Element\Textarea',
					'options' => array(
							//	'label' => 'Observaciones',
			
					),
					'attributes' => array(
							'placeholder' => 'Agregue alguna observación aquí..',
					),
			
			),
					array (
							'priority' => 500,
					)
			);
		}


		if ($aprobarEnabled){
			//This is the submit button
			$this->add(array(
					'name' => 'Aprobar',
					'type' => 'Zend\Form\Element\Submit',
					'attributes' => array(
							'value' => 'Aprobar',
							'required' => 'false',
							'onclick' => "return confirm('Está seguro que desea aprobar esta solicitud?');"
			
					),
			),
					array (
							'priority' => 495,
					)
			);
		}

		if ($vistoBuenoEnabled){
			$this->add(array(
					'name' => 'VistoBueno',
					'type' => 'Zend\Form\Element\Submit',
					'attributes' => array(
							'value' => 'Visto Bueno y Derivar',
							'required' => 'false',
			
					),
			),
					array (
							'priority' => 495,
					)
			);
		}


		if ($pendienteEnabled){
			$this->add(array(
					'name' => 'Pendiente',
					'type' => 'Zend\Form\Element\Submit',
					'attributes' => array(
							'value' => 'Pendiente',
							'required' => 'false',
			
					),
			),
					array (
							'priority' => 490,
					)
			);
		}
		
		if ($anularEnabled){
			$this->add(array(
					'name' => 'Anular',
					'type' => 'Zend\Form\Element\Submit',
					'attributes' => array(
							'value' => 'Anular',
							'required' => 'false',
							'onclick' => "return confirm('Está seguro que desea anular esta solicitud?');"
			
					),
			),
					array (
							'priority' => 480,
					)
			);
		}

		if ($rechazarEnabled){
			$this->add(array(
					'name' => 'Rechazar',
					'type' => 'Zend\Form\Element\Submit',
					'attributes' => array(
							'value' => 'Rechazar',
							'required' => 'false',
							'onclick' => "return confirm('Está seguro que desea rechazar esta solicitud?');"
			
					),
			),
					array (
							'priority' => 470,
					)
			);
		}
		
		if ($enviarCorreoEnabled){
			$this->add(array(
					'name' => 'EnviarCorreo',
					'type' => 'Zend\Form\Element\Submit',
					'attributes' => array(
							'value' => 'Enviar Correo',
							'required' => 'false',
			
					),
			),
					array (
							'priority' => 450,
					)
			);
		}

		$this->add(array(
				'name' => 'Imprimir',
				'type' => 'Zend\Form\Element\Submit',
				'attributes' => array(
						'value' => 'Imprimir',
						'required' => 'false',

				),
		),
				array (
						'priority' => 440,
				)
		);
		
		$this->add(array(
				'name' => 'Salir',
				'type' => 'Zend\Form\Element\Submit',
				'attributes' => array(
						'value' => 'Volver a Lista de Solicitudes',
						'required' => 'false',
		
				),
		),
				array (
						'priority' => 430,
				)
		);


		// This is the special code that protects our form beign submitted from automated scripts
		$this->add(array(
				'name' => 'csrf',
				'type' => 'Zend\Form\Element\Csrf',
		));



	}

	public function getInputFilter()
	{
		if (! $this->filter) {
			$inputFilter = new InputFilter();
			$factory = new InputFactory ();

			if ($this->observacionesEnabled){
				$inputFilter->add ( $factory->createInput ( array (
						'allow_empty' => true,
						'name' => 'observaciones',
						'filters' => array (
								array (
										'name' => 'StripTags'
								),
								array (
										'name' => 'StringTrim'
								)
						),
						'validators' => array (
								array (
										'name' => 'Alnum',
	// 									'options' => array (
	// 											'messages' => array (
	// 													'notAlnum' => 'Se requieren sólo números y letras'
	// 											),
	// 											'allowWhiteSpace' => true,
	// 									)
								),
						)
				) ) );
			}

			$this->filter = $inputFilter;
		}

		return $this->filter;
	}


	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception('It is not allowed to set the input filter');
	}

}
