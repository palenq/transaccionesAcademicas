<?php
namespace Solicitud\Form\Formulario;

use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory as InputFactory;
use Zend\Db\Adapter\AdapterInterface;


/* Solicitud de Inclusión el Lista, que hereda de la clase Solicitud */
class SolicitudInclusionLista extends Solicitud
{
	//parámetros del constructor: adaptadores de la base de datos, y el identificador del usuario logueado
	public function __construct(AdapterInterface $dbadapter, $idUsuario, AdapterInterface $sapientiaDbadapter) { //parámetro del constructor: adaptador de la base de datos

		// Le pasamos los respectivos parámetros al constructor del padre
		parent::__construct($name = 'inclusionLista', $dbadapter, $idUsuario, $sapientiaDbadapter);

		$this->setAttribute('method', 'post');
		
		
		//////////////////////***********INICIO Extracción de Datos**************/////////////////

		$usuarioLogueado = $idUsuario;
	


		//////////////////////***********FIN Extracción de Datos**************/////////////////
		
		/* A partir de aquí agregamos los elementos particulares a esta solicitud */
		$this->add(array(
				'name' => 'asignatura',
				'type' => 'Zend\Form\Element\Select',
				'options' => array(
						'label' => 'Asignatura:',
						'empty_option' => 'Seleccione una asignatura..',
						'value_options' => $selectDataMat,
				),
				'attributes' => array(
						'required' => 'required',
						'id' => 'asignatura',
				),
		),
				array (
						'priority' => 350,
				)
		);
		
		$this->add(array(
				'name' => 'seccion',
				'type' => 'Zend\Form\Element\Select',
				'options' => array(
						'label' => 'Sección',
						'empty_option' => 'Seleccione la sección..',
		
				),
				'attributes' => array(
						'required' => 'required',
						'id' => 'seccion',
						
				),
		),
				array (
						'priority' => 345,
				)
		);

		$this->add(array(
				'type' => 'Zend\Form\Element\Radio',
				'name' => 'motivo',
				'options' => array(
						'label' => 'Motivo',
						'value_options' => array(
								'Beca' => 'Beca',
								'Otro' => 'Otro'
						),
				),
				'attributes' => array(
						'required' => 'required',
						'id' => 'motivo',
						
				),		
		),
				array (
						'priority' => 340,
				)
			
		);

		$this->add(array(
				'name' => 'especificacion_motivo',
				'type' => 'Zend\Form\Element\Textarea',
				'options' => array(
						//'label' => 'Especificación de Motivo'
				),
				'attributes' => array(
						'placeholder' => 'Agregue alguna información adicional aquí...',
						'id' => 'especificacion_motivo',
						//'required' => 'required',
						
				)
		),
				array (
						'priority' => 330,
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
			// DEBEMOS inicializar filter del padre
			$inputFilter = parent::getInputFilter();
			$factory = new InputFactory ();

			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'asignatura',
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
									'name' => 'notEmpty',
							),
							array (
									'name' => 'alnum',
									'options' => array (
											'messages' => array (
													'notAlnum' => 'Se requieren sólo números y letras'
											),
											'allowWhiteSpace' => true,
									)
							),
							
					)
			) ) );
			
			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'motivo',
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
									'name' => 'alnum',
									'options' => array (
											'messages' => array (
													'notAlnum' => 'Se requieren sólo números y letras'
											),
											'allowWhiteSpace' => true,
									)
							),
								
					)
			) ) );

			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'seccion',
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
									'name' => 'NotEmpty',
							),
							array (
									'name' => 'alnum',
									'options' => array (
											'messages' => array (
													'notAlnum' => 'Se requieren sólo números y letras'
											),
											'allowWhiteSpace' => true,
									)
							),
			
					)
			) ) );
				

			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'especificacion_motivo',
					'allow_empty' => true,
					'filters' => array (
							array (
									'name' => 'StripTags'
							),
							array (
									'name' => 'StringTrim'
							)
					),

			) ) );



			// @todo: posiblemente agregar filtros a los demas campos

			$this->filter = $inputFilter;
		}


		return $this->filter;
	}


	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception('It is not allowed to set the input filter');
	}


}