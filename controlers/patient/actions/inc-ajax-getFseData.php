<?php
/*
 * This file is part of MedShakeEHR.
 *
 * Copyright (c) 2018
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
 * Patient > ajax : obtenir les data nécessaires à l'établissement d'une FSE
 *
 * @author Bertrand Boutillier <b.boutillier@gmail.com>
 */

// objet paiement
$paiem = new msObjet;
$dataPaiem = $paiem->getObjetAndSons($_GET['objetID'], 'name');
$actes = json_decode($dataPaiem['regleDetailsActes']['value'], TRUE);

// data praticien
$prat = new msPeople;
$prat->setToID($p['user']['id']);
$pratData = $prat->getSimpleAdminDatasByName();

// data patient
$patient = new msPeople;
$patient->setToID($paiem->getObjetDataByID($_GET['objetID'], ['toID'])['toID']);

// relation pour recherche du médecin traitant déclaré
$ips='';
$medecin_traitant_declare='false';
$prenom_1180='';
$nom_1180='';
if($relations = $patient->getRelationsWithPros()) {
  foreach($relations as $v) {
    if($v['typeRelation']=='MTD' or $v['typeRelation']=='MT') {
      $medecin_traitant_declare='true';
      $prenom_1180=$v['prenom'];
      $nom_1180=$v['nom'];
      if($v['typeRelation']=='MTD') {
        // si le MT et le prat qui agit
        if($p['user']['id']==$v['pratID']) $ips='T';
        break;
      }
    }
  }
}

$msehrJsonData=array(
  'actes'=>$actes,
  'returnUrl'=>$p['config']['protocol'].$p['config']['host'].$p['config']['urlHostSuffixe'].'/rest/callbackFse/',
  'returnData'=>array(
    'objetID'=>$_GET['objetID'],
    'validationHash'=>md5($dataPaiem['regleDetailsActes']['registerDate'].$_GET['objetID'].$dataPaiem['regleDetailsActes']['typeID']),
    'data'=>''
  )
);

$data=array(
  'formFields'=>array(
    'ips'=>$ips,
    'numero_rpps'=>$pratData['rpps'],
    'medecin_traitant_declare'=>$medecin_traitant_declare,
    'prenom_1180'=>$prenom_1180,
    'nom_1180'=>$nom_1180,
    'msehrParams'=> htmlentities(json_encode($msehrJsonData))
  ),
  'actes'=>$actes,
  'jsonMsehrParams'=>$msehrJsonData
);

header('Content-Type: application/json');
exit(json_encode($data));
