<?php 
	interface interfacedto{
		namespace app\core\models\dto\base;
		/**		
		* devuelve un arreglo con todos los campos de la tabla
		*return array arreglo con los campos de la tabla
		*/
		public function toArray():array;
		
	}
?>