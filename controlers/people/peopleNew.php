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
 * people :  créer un individus
 * soit en mode patient -> formulaire baseNewPatient
 * soit en mode pro -> formualire baseNewPro
 *
 * @author Bertrand Boutillier <b.boutillier@gmail.com>
 * @contrib fr33z00 <https://github.com/fr33z00>
 */

$debug='';
$template="peopleNew";

$p['page']['porp']=$match['params']['porp'];

if ($p['page']['porp']=='patient') {
    $p['page']['formIN']='baseNewPatient';
} elseif ($p['page']['porp']=='pro') {
    $p['page']['formIN']='baseNewPro';
}


$formpatient = new msForm();
$formpatient->setFormIDbyName($p['page']['formIN']);
if (isset($_SESSION['form'][$p['page']['formIN']]['formValues'])) {
    $formpatient->setPrevalues($_SESSION['form'][$p['page']['formIN']]['formValues']);
}

//si formulaire pro
if ($p['page']['porp']=='pro') {

  //si jeux de valeurs normées présents
  if(is_file($p['homepath'].'ressources/JDV/JDV_J01-XdsAuthorSpecialty-CI-SIS.xml')) {
    $codes = msExternalData::getJdvDataFromXml('JDV_J01-XdsAuthorSpecialty-CI-SIS.xml');
    $optionsInject['p_PSCodeProSpe']=array_column($codes, 'displayName', 'code');
  }

  if(is_file($p['homepath'].'ressources/JDV/JDV_J02-HealthcareFacilityTypeCode_CI-SIS.xml')) {
    $codes = msExternalData::getJdvDataFromXml('JDV_J02-HealthcareFacilityTypeCode_CI-SIS.xml');
    $optionsInject['p_PSCodeStructureExercice']=array_column($codes, 'displayName', 'code');
  }
  if(!empty($optionsInject)) $formpatient->setOptionsForSelect($optionsInject);
}

$p['page']['form']=$formpatient->getForm();
$formpatient->addSubmitToForm($p['page']['form'], 'btn-primary');
