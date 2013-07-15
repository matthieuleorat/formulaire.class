<?php

class formulaire {
	public $data;
	
	/* ********************** */
	/* Définition des données */
	/* ********************** */
	/* 
		$d : Données du formulaire
		$s : Donnees de la balise form -> array (id du formulaire,Page de destination,methode, classe)
		$s : Donnees du bouton submit -> array(id, value, classe)
	*/
	function setData($d,$f,$s){
		$this->data = $d;
		$this->data[$f[0]] = array('form',$f[1],$f[2],$f[3]);
		$this->data[$s[0]] = array('submit',$s[1],$s[2]);
	}
	
	function getData(){
		return $this->data;
	}
	
	/* ************************ */
	/* Génération du formulaire */
	/* ************************ */
	/*
	 format attendu :
		array(
			id_champ => array(type,label,value,class)
		)
	*/
	function genererForm(){
		$f = '';
		foreach($this->data as $k => $v){
			switch ($v[0]) {
				case 'form':
					$bf = formulaire::genererBaliseForm($k,$v[1],$v[2],$v[3]);
				break;
				case 'hidden':
					$f .= formulaire::genererFormHidden($k);
				break;
				case 'text':
					$f .= formulaire::genererFormText($k,$v[1],$v[2],$v[3],$v[4]);
				break;
				case 'select':
					$f .= formulaire::genererFormSelect($k,$v[1],$v[2],$v[3],$v[4],$v[5],$v[6]);
				break;
				case 'radio':
					$f .= formulaire::genererFormRadio($k,$v[1],$v[2],$v[3]);
				break;
				case 'search':
					$f .= formulaire::genererFormSearch($k,$v[1],$v[2],$v[3]);
				break;
				case 'tel':
					$f .= formulaire::genererFormTel($k,$v[1],$v[2],$v[3]);
				break;
				case 'email':
					$f .= formulaire::genererFormEmail($k,$v[1],$v[2],$v[3]);
				break;
				case 'url':
					$f .= formulaire::genererFormUrl($k,$v[1],$v[2],$v[3]);
				break;
				case 'date':
					$f .= formulaire::genererFormDate($k,$v[1],$v[2],$v[3]);
				break;
				case 'Number':
					$f .= formulaire::genererFormNumber($k,$v[1],$v[2],$v[3]);
				break;
				case 'range':
					$f .= formulaire::genererFormRange($k,$v[1],$v[2],$v[3]);
				break;
				case 'color':
					$f .= formulaire::genererFormColor($k,$v[1],$v[2],$v[3]);
				break;
				case 'file':
					$f .= formulaire::genererFormFile($k,$v[1],$v[2],$v[3]);
				break;
				case 'submit':
					$fs = formulaire::genererFormSubmit($k,$v[1],$v[2]);
				break;
			}
		}
		return $bf.$f.$fs."</form>";
	}
	
	/* ******************* */
	/* Generer balise form */
	/* ******************* */
	/*
		$id 	: id du formulaire
		$action	: Page de destination
		$method	: methode POST par défaut
		$dis	: disable le champ -> true ou false
		$class 	: Class du champ
	*/
	private function genererBaliseForm($id,$action,$method='POST',$class=''){
		return "<form id='".$id."' method='".$method."' action='".$action."' class='".$class."'>";
	}
	
	/* ****************** */
	/* Generer input text */
	/* ****************** */
	/*
		$id 	: id du champ
		$label	: Nom du champ
		$value	: valeur à charger
		$class 	: Classe du champ
	*/
	private function genererFormText($id,$label,$value='',$dis=false, $class=''){
		$a = '';
		if(!$dis){$a .= "disabled";}
		return "<p>
			<label for='".$id."'>".$label."</label>
			<input ".$a." type='text' name='".$id."' id='".$id."' value='".$value."' class='".$class."'/>
			</p>";
	}
	
	/* ******************** */
	/* Generer input hidden */
	/* ******************** */
	/*
		$id 	: id du champ
		$value	: valeur à charger
	*/
	private function genererFormHidden($id,$value=''){
		return "<input type='hidden' name='".$id."' id='".$id."' value='".$value."' />";
	}
	
	/* **************************** */
	/* Generer une liste déroulante */
	/* **************************** */
	/*
		$id 	: id du champ
		$label 	: label
		$d 		: Donnees la liste
		$ds 	: Donnees préselectionnees
		$multi 	: Select Multiple -> False par défaut, le nb de champ à afficher sinon
		$dis 	: disable le champ -> true ou false
		$class 	: Classe du champ
	*/
	private function genererFormSelect($id,$label,$d,$ds=0,$multi=false,$dis=false,$class=''){
		$a = '';
		if(is_int($multi)){$a .= "multiple size='".$multi."'";}
		if(!$dis){$a .= "disabled";}
		$r = "<p class='".$class."'>
			<label for='".$id."'>".$label."</label>
			<select name='".$id."' id='".$id."' ".$a." >
			<option value=''></option>";
		foreach($d as $k => $v){
			$r .= "<option value='".$k."'";
			if(is_array($ds)){
				if(in_array($k, $ds)){$r .= ' selected ';}
			}elseif(is_int($ds)){
				if($ds == $k){$r .= ' selected ';}
			}
			$r .= ">".$v."</option>";
		}
		$r .= "</select>
				</p>";
		return $r;
	}
	
	/* ******************** */
	/* Generer bouton radio */
	/* ******************** */
	/*
		$name 	:
		$label 	: 
		$d 		: array (value, label)
		$dis 	: disable le champ -> true ou false
	*/
	private function genererFormRadio($name,$label,$d,$dis=false){
		$a = '';
		if(!$dis){$a .= "disabled";}
		$r = '<label>'.$label.'</label><p>';
		foreach($d as $id => $label){
			$r .= '<input type="radio" '.$a.' name="'.$name.'" id="'.$id.'" value="'.$id.'">';
			$r .= '<label for="'.$id.'">'.$label.'</label></br>';
		}
		$r .= '</p>';
		return $r;
	}
	
	/* ******************** */
	/* Generer input Submit */
	/* ******************** */
	/*
		$id 	: Id du champ
		$value	: Valeur à charger
		$dis : disable le champ -> true ou false
		$class 	: Classe du champ
		$dis 	: disable le champ -> true ou false
	*/
	private function genererFormSubmit($id,$value='Envoyer',$dis=false,$class=''){
		$a = '';
		if(!$dis){$a .= "disabled";}
		return "<input ".$a." type='submit' id='".$id."' name='".$id."' value='".$value."' class='".$class."'/>";
	}
	
	/* ******************** */
	/* Generer input search */
	/* ******************** */
	/*
		$id 			: id du champ
		$label			: Nom du champ
		$value			: valeur à charger
		$dis 	: disable le champ -> true ou false
		$class 			: Classe du champ
		$placeholder	: PlaceHolder
	*/
	private function genererFormSearch($id,$label,$value='',$dis=false, $class='',$placeholder=''){
		$a = '';
		if(!$dis){$a .= "disabled";}
		return "<p>
			<label for='".$id."'>".$label."</label>
			<input ".$a." type='search' placeholder='".$placeholder."' name='".$id."' id='".$id."' value='".$value."' class='".$class."'/>
			</p>";
	}
	
	/* ***************** */
	/* Generer input tel */
	/* ***************** */
	/*
		$id 	: id du champ
		$label	: Nom du champ
		$value	: valeur à charger
		$dis 	: disable le champ -> true ou false
		$class 	: Classe du champ
	*/
	private function genererFormTel($id,$label,$value='',$dis=false, $class=''){
		$a = '';
		if(!$dis){$a .= "disabled";}
		return "<p>
			<label for='".$id."'>".$label."</label>
			<input ".$a." type='tel' name='".$id."' id='".$id."' value='".$value."' class='".$class."' pattern='^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$' />
			</p>";
	}
	
	/* ******************* */
	/* Generer input email */
	/* ******************* */
	/*
		$id 	: id du champ
		$label	: Nom du champ
		$value	: valeur à charger
		$dis 	: disable le champ -> true ou false
		$class 	: Classe du champ
	*/
	private function genererFormEmail($id,$label,$value='',$dis=false, $class=''){
		$a = '';
		if(!$dis){$a .= "disabled";}
		return "<p>
			<label for='".$id."'>".$label."</label>
			<input ".$a." type='email' name='".$id."' id='".$id."' value='".$value."' class='".$class."'/>
			</p>";
	}
	
	/* ***************** */
	/* Generer input Url */
	/* ***************** */
	/*
		$id 	: id du champ
		$label	: Nom du champ
		$value	: valeur à charger
		$dis 	: disable le champ -> true ou false
		$class 	: Classe du champ
	*/
	private function genererFormUrl($id,$label,$value='',$dis=false, $class=''){
		$a = '';
		if(!$dis){$a .= "disabled";}
		return "<p>
			<label for='".$id."'>".$label."</label>
			<input ".$a." type='url' name='".$id."' id='".$id."' value='".$value."' class='".$class."'/>
			</p>";
	}
	
	/* ****************** */
	/* Generer input Date */
	/* ****************** */
	/*
		$id 	: id du champ
		$label	: Nom du champ
		$value	: valeur à charger
		$dis 	: disable le champ -> true ou false
		$class 	: Classe du champ
		$min	:
		$max	:
	*/
	private function genererFormDate($id,$label,$value='',$dis=false, $class='',$min='',$max=''){
		$a = '';
		if(!$dis){$a .= "disabled";}
		if(!empty($min)){$a .= " min='".$min."' ";}
		if(!empty($max)){$a .= " max='".$max."' ";}
		return "<p>
			<label for='".$id."'>".$label."</label>
			<input ".$a." type='date' name='".$id."' id='".$id."' value='".$value."' class='".$class."'/>
			</p>";
	}
	
	/* ******************** */
	/* Generer input Number */
	/* ******************** */
	/*
		$id 	: id du champ
		$label	: Nom du champ
		$value	: valeur à charger
		$dis 	: disable le champ -> true ou false
		$class 	: Classe du champ
		$min	:
		$max	:
		$step	:
	*/
	private function genererFormNumber($id,$label,$value='',$dis=false, $class='',$step='',$min='',$max=''){
		$a = '';
		if(!$dis){$a .= "disabled";}
		if(!empty($step)){$a .= " step='".$step."' ";}
		if(!empty($min)){$a .= " min='".$min."' ";}
		if(!empty($max)){$a .= " max='".$max."' ";}
		return "<p>
			<label for='".$id."'>".$label."</label>
			<input ".$a." type='number' name='".$id."' id='".$id."' value='".$value."' class='".$class."'/>
			</p>";
	}
	
	/* ******************* */
	/* Generer input Range */
	/* ******************* */
	/*
		$id 	: id du champ
		$label	: Nom du champ
		$value	: valeur à charger
		$dis 	: disable le champ -> true ou false
		$class 	: Classe du champ
	*/
	private function genererFormRange($id,$label,$value='',$dis=false, $class='',$step='',$min='',$max=''){
		$a = '';
		if(!$dis){$a .= "disabled";}
		if(!empty($step)){$a .= " step='".$step."' ";}
		if(!empty($min)){$a .= " min='".$min."' ";}
		if(!empty($max)){$a .= " max='".$max."' ";}
		return "<p>
			<label for='".$id."'>".$label."</label>
			<input ".$a." type='range' name='".$id."' id='".$id."' value='".$value."' class='".$class."'/>
			</p>";
	}
	
	/* ******************* */
	/* Generer input color */
	/* ******************* */
	/*
		$id 	: id du champ
		$label	: Nom du champ
		$value	: valeur à charger
		$dis 	: disable le champ -> true ou false
		$class 	: Classe du champ
	*/
	private function genererFormColor($id,$label,$value='',$dis=false, $class=''){
		$a = '';
		if(!$dis){$a .= "disabled";}
		return "<p>
			<label for='".$id."'>".$label."</label>
			<input ".$a." type='color' name='".$id."' id='".$id."' value='".$value."' class='".$class."'/>
			</p>";
	}
	
	/* ************** */
	/* Generer output */
	/* ************** */
	/*
		$id 	: id du champ
		$label	: Nom du champ
		$dis 	: disable le champ -> true ou false
		$value	: valeur à charger
		$class 	: Classe du champ
	*/
	private function genererFormOutput($id,$label,$dis=false, $class='',$for=''){
		$a = '';
		if(!$dis){$a .= "disabled";}
		if(!empty($for)){$a .= " for='".$for."' ";}
		return "<p>
			<label for='".$id."'>".$label."</label>
			<input ".$a." type='color' name='".$id."' id='".$id."' class='".$class."'/>
			</p>";
	}
	
	/* ****************** */
	/* Generer input file */
	/* ****************** */
	/*
		$id 	: id du champ
		$label	: Nom du champ
		$value	: valeur à charger
		$dis 	: disable le champ -> true ou false
		$class 	: Classe du champ
		$multiple :
	*/
	private function genererFormFile($id,$label,$value='',$dis=false, $class='', $multiple=false){
		$a = '';
		if(!$dis){$a .= " disabled ";}
		if(!$multiple){$a .= " multiple ";}
		return "<p>
			<label for='".$id."'>".$label."</label>
			<input ".$a." type='file' name='".$id."' id='".$id."' class='".$class."' />
		</p>";
	}

}
?>