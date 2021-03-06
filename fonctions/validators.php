<?php
/*
 * This file is part of MedShakeEHR.
 *
 * Copyright (c) 2017
 * Bertrand Boutillier <b.boutillier@gmail.com>
 * http://www.medshake.net
 *
 * MedShakeEHR is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * MedShakeEHR is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with MedShakeEHR.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Compléments pour la validation des data avec GUMP
 * <https://github.com/Wixel/GUMP>
 *
 * @author Bertrand Boutillier <b.boutillier@gmail.com>
 */



GUMP::add_validator("identite", function($field, $input, $param = NULL) {
		if (empty($input[$field])) return TRUE;
		$find=preg_match('/^([a-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ\-\'\ ])+$/i', $input[$field]);
		if ($find!='1') return FALSE; else return TRUE;
	}, 'Ce champ a une mauvaise syntaxe !');

GUMP::add_validator("mobilphone", function($field, $input, $param = NULL) {
		if (empty($input[$field])) return TRUE;
		$find=preg_match('/^0[6-7]{1}(([0-9]{2}){4})|((\s[0-9]{2}){4})|((-[0-9]{2}){4})$/i', $input[$field]);
		if ($find!='1') return FALSE; else return TRUE;
	}, 'Ce numéro de téléphone mobile n\'est pas valide !');

GUMP::add_validator("phone", function($field, $input, $param = NULL) {
		if (empty($input[$field])) return TRUE;
		$find=preg_match('/^0[1-6]{1}(([0-9]{2}){4})|((\s[0-9]{2}){4})|((-[0-9]{2}){4})$/i', $input[$field]);
		if ($find!='1') return FALSE; else return TRUE;
	}, 'Ce numéro de téléphone n\'est pas valide !');

GUMP::add_validator("presence_bdd", function($field, $input, $param = NULL) {
		if (empty($input[$field])) return TRUE;
		if (msSQL::sqlUniqueChamp("select $field from $param where $field='".msSQL::cleanVar($input[$field])."' limit 1") ) return FALSE;
	}, 'Ce nom est déjà utilisé !');

GUMP::add_validator("validedate", function($field, $input, $param = NULL) {
		msTools::validateDate($input[$field], $param);
	}, 'Cette date n\'est pas valide !');


?>
