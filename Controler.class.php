<?php
error_reporting(0);
/**
 * Class Controler
 * Gère les requêtes HTTP
 * 
 * @author Jonathan Martel
 * @version 1.0
 * @update 2019-01-21
 * @license Creative Commons BY-NC 3.0 (Licence Creative Commons Attribution - Pas d’utilisation commerciale 3.0 non transposé)
 * @license http://creativecommons.org/licenses/by-nc/3.0/deed.fr
 * 
 */

class Controler 
{
		/**
		 * Traite la requête
		 * @return void
		 */
		public function gerer()
		{
			
			switch ($_GET['requete']) {
				case 'accueil':
					$this->accueil();
					break;
				case 'listeBouteille':
					$this->verificationUtilisateurConnecter();
					$this->listeBouteille();
					break;
				case 'autocompleteBouteille':
					$this->verificationUtilisateurConnecter();
					$this->autocompleteBouteille();
					break;
				case 'ajouterNouvelleBouteilleCellier':
					$this->verificationUtilisateurConnecter();
					$this->ajouterNouvelleBouteilleCellier();
					break;
					case 'ajouterNouvelleBouteilleListe':
						$this->verificationUtilisateurConnecter();
						$this->ajouterNouvelleBouteilleListe();
						break;
					case 'listeAchat':
							$this->verificationUtilisateurConnecter();
							$this->listeAchat();
					break;
					case 'supprimerBouteilleListe':
						$this->verificationUtilisateurConnecter();
						$this->supprimerBouteilleListe();
					break;
				case 'ajouterBouteilleCellier':
					$this->verificationUtilisateurConnecter();
					$this->ajouterBouteilleCellier();
					break;
				case 'boireBouteilleCellier':
					$this->verificationUtilisateurConnecter();
					$this->boireBouteilleCellier();
					break;
				case 'modifierBouteilleCellier':
					$this->verificationUtilisateurConnecter();
					$this->modifierBouteilleCellier();
					break;
				case 'supprimerBouteilleCellier':
					$this->verificationUtilisateurConnecter();
					$this->supprimerBouteilleCellier();
					break;
				case 'authentification':
					$this->authentification();
					break;
				case 'enregistrement':
					$this->enregistrement();
					break;
				case 'ajouterNouveauCellier':
					$this->verificationUtilisateurConnecter();
					$this->ajouterNouveauCellier();
					break;
				case 'supprimerCellier':
					$this->verificationUtilisateurConnecter();
					$this->supprimerCellier();
					break;
				case 'modifierCellier':
					$this->verificationUtilisateurConnecter();
					$this->modifierCellier();
					break;
				case 'cellier':
					$this->verificationUtilisateurConnecter();
					$this->cellier();
					break;
				case 'monCompte':
					$this->verificationUtilisateurConnecter();
					$this->monCompte();
					break;
				case 'signalerErreur':
					$this->verificationUtilisateurConnecter();
					$this->signalerErreur();
					break;
				case 'admin':
					$this->verificationUtilisateurConnecter();
					$this->verificationAdmin();
					$this->admin();
					break;
				case 'admin/utilisateur':
					$this->verificationUtilisateurConnecter();
					$this->verificationAdmin();
					$this->adminUtilisateur();
					break;
				case 'admin/modifierBouteille':
					$this->verificationUtilisateurConnecter();
					$this->verificationAdmin();
					$this->adminModifierBouteille();
					break;
				case 'admin/ajouterDroit':
					$this->verificationUtilisateurConnecter();
					$this->verificationAdmin();
					$this->ajouterDroitAdmin();
					break;
				case 'admin/supprimerUtilisateur':
					$this->verificationUtilisateurConnecter();
					$this->verificationAdmin();
					$this->supprimerUtilisateur();
					break;
				case 'admin/importation':
					$this->verificationUtilisateurConnecter();
					$this->verificationAdmin();
					$this->importationBouteille();
					break;
				case 'admin/statistique':
					$this->verificationUtilisateurConnecter();
					$this->verificationAdmin();
					$this->statistiqueUtilisateur();
					break;
				case 'admin/supprimerMessage':
					$this->verificationUtilisateurConnecter();
					$this->verificationAdmin();
					$this->supprimerMessage();
					break;
				default:
					$this->authentification();
					break;
			}
		}

		//Fonction permetant de creer une session d"utilisateur
		private function createSessionUtilisateur($user){

			//Permet de demarrer une session et inserer des valeurs
			session_start();
			$_SESSION['users_id']  = $user['users_id'];
			$_SESSION['users_login']  = $user['users_login'];
			$_SESSION['users_type']  = $user['users_type'];

			//Execute une requete au modele pour avoir
			//le data de l'utilisateur
			$utilisateur = new Utilisateur();
			$dataUtilisateur = $utilisateur->getCellierUtilisateur($user['users_id']);
			
			//Insere le id du cellier dans une varaible session
			$_SESSION['cellier_id'] = $dataUtilisateur[0]['id'];
			
			//Redirige vers l'accueil de l'utilisateur
			if($user['users_type'] == "admin"){
				header('location:' . BASEURL . '?requete=admin');
			}else{
				header('location:' . BASEURL . '?requete=cellier');
			}
			
		}

		private function accueil(){
			
			include("vues/enteteAcceuil.php");
			////////////////////modif hind//////////////
			include("vues/acceuil.php");
			include("vues/pied.php");
                  
		}

		//Fonction permetant d'authentifier les utilisateur
		private function authentification(){
			if(isset($_GET['user'])){
				$lastUser= $_GET['user'];
			}else{
				$lastUser = "";
			}
			$data = [
				'identifiant' => "",
				'motDePasse' =>  "",
				'identifiantErreur' => "",
				'motDePasseErreur' => ""
			];

			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				//Sanitisation du post
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
				
				$data = [
					'identifiant' => trim($_POST['identifiant']),
					'motDePasse' =>  trim($_POST['motDePasse']),
					'identifiantErreur' => "",
					'motDePasseErreur' => ""
				];
		
				//Validation du identifiant
				if(empty($data['identifiant'])){
					$data['identifiantErreur'] = 'Veuillez entrer un identifiant';
				}

				//Validation du mot de passe
				if(empty($data['motDePasse'])){
					$data['motDePasseErreur'] = 'Veuillez entrer un mot de passe';
				}

				//Verifie si il y a aucune erreur
				if(empty($data['identifiantErreur']) && empty($data['motDePasseErreur'])){
					
					//Execute la requete vers le modele
					$utilisateur = new Utilisateur();
					$utilisateurConnecte = $utilisateur->controleUtilisateur($data);

					if($utilisateurConnecte) {
						$this->createSessionUtilisateur($utilisateurConnecte);
					}else{
						$data['motDePasseErreur'] = "Identifiant ou mot de passe incorect";
					}

				}

			} else {
				$data = [
					'identifiant' => "",
					'motDePasse' =>  "",
					'identifiantErreur' => "",
					'motDePasseErreur' => ""
				];
			}
			//Load la vue pour authentification
			//Load la vue pour authentification
			include("vues/enteteAcceuil.php");
			include("vues/authentification.php");
			include("vues/pied.php");
		}

		//Fonction permetant enregistrer des utilisateur
		private function enregistrement(){

			$data = [
				'identifiant' => "",
				'motDePasse' => "",
				'confirmMotDePasse' => "",
				'identifiantErreur' => "",
				'motDePasseErreur' => "",
				'confirmMotDePasseErreur' => ""
			];

			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				
				$data = $this->verifierDonnee(trim($_POST['identifiant']), trim($_POST['motDePasse']), trim($_POST['confirmMotDePasse']));
				
				//Verifie si il y a des erreurs
				if(empty($data['identifiantErreur']) && empty($data['motDePasseErreur']) && empty($data['confirmMotDePasseErreur'])){
					$utilisateur = new Utilisateur();
					//Insere l'utilisateur dans la DB
					if($utilisateur->enregistrementUtilisateur($data) == true){
						//Redirige vers le login
						//$this->authentification();
						header('location:' . BASEURL . '?requete=authentification&user='.$_POST['identifiant'].'');
					}
					
				}
			}

			//Load la vue pour register
			include("vues/enteteAcceuil.php");
			include("vues/register.php");
			include("vues/pied.php");

		}
		

		//Fonction permetant de verifier si un utilisateur est connecter
		private function verificationUtilisateurConnecter(){
			session_start();
			//verifie si un utlisateur est connecter
			if(empty($_SESSION['users_id'])){
				header('Location: '. BASEURL.'?requete=authentification');
			}
		}

		//Fonction permetant de verifier si un utilisateur est connecter
		private function deconnexionUtilisateur(){

			//Unset les donnees de la variable $_SESSION
			unset($_SESSION['users_id']);
			unset($_SESSION['users_login']);
			unset($_SESSION['users_type']);
			session_destroy();

			//Redirige vers l'authentification
			header('Location: '. BASEURL .'?requete=authentification');
		}

		//Fonction qui permet a l'utlisateur de gerer son compte
		private function monCompte(){
			$utilisateur = new Utilisateur();
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				//Verifie si l'utilisateur veut modifier
				if(isset($_POST['modifierUtilisateur'])){

					//Initialise un $array qui vas permettre de confirmer le mot de passe l'usager
					$array = [
						'identifiant' => $_SESSION['users_login'],
						'motDePasse' => trim($_POST['ancienMotDePasse']),
						'ancienMotDePasseErreur'=>""
					];
			
					//Permet de comfirmer si l'utilisateur à bien entré son mot de passe
					if($utilisateur->controleUtilisateur($array)){
						
						//Permet de vérifier les données de l'utilisateur
						$data = $this->verifierDonnee(trim($_POST['nom']),trim($_POST['motDePasse']), trim($_POST['motDePasseConf']));
					
						//Verifie si il y a des erreurs
						if(empty($data['identifiantErreur']) && empty($data['motDePasseErreur']) && empty($data['confirmMotDePasseErreur'])){
							//Modifie l'utilisateur dans la DB
							if($utilisateur->modificationUtilisateur($data, $_SESSION['users_id']) == true){
								$data['confirmation'] = "Modification Bien effectue";
							}
						}
					}else{
						//Mauvais mot de passe
						$array['ancienMotDePasseErreur'] = "Vous n'avez pas entrée le bon mot de passe";
					}

					
				}
				//Verifie si l'utilisateur veut supprimer
				if(isset($_POST['supprimerUtilisateur'])){
					$array = [
						'identifiant' => $_SESSION['users_login'],
						'motDePasse' => trim($_POST['ancienMotDePasseSupp']),
						'ancienMotDePasseErreurSupp'=>""
					];
					//Permet de confirmer le mot de passe de l'utilisateur avant la suppression
					if($utilisateur->controleUtilisateur($array)){
						if($utilisateur->supprimerUtilisateur($_SESSION['users_id'])){
							header('Location: '. BASEURL .'?requete=authentification');
						}else{
							//Une erreur est sruvenue
						}
					}else{
						//Mauvais mot de passe
						$array['ancienMotDePasseErreurSupp'] = "Vous n'avez pas entrée le bon mot de passe";
					}
				}
			}
			include("vues/entete.php");
			include("vues/monCompte.php");
			include("vues/pied.php");
		}

		//Fonction permertant de verifier les données envoyé par un utilisateur
		private function verifierDonnee($identifiant, $motDePasse, $confMotDePasse){
			$data = [
				'identifiant' => $identifiant,
				'motDePasse' => $motDePasse,
				'confirmMotDePasse' => $confMotDePasse,
				'identifiantErreur' => "",
				'motDePasseErreur' => "",
				'confirmMotDePasseErreur' => "",
				'confirmation' => ""
			];
			//Validation du identifiant
			$expReg = "/^[a-zA-Z0-9]*$/";
			if(empty($data['identifiant'])){
				$data['identifiantErreur'] = 'Veuillez entrer un identifiant';
			}elseif(!preg_match($expReg, $data['identifiant'])){
				$data['identifiantErreur'] = 'Veuillez entrer que des lettres ou chiffres';
			}

			//Validation du mot de passe
			$expReg = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/i";
			if(empty($data['motDePasse'])){
				$data['motDePasseErreur'] = 'Veuillez entrer un mot de passe';
			}elseif(strlen($data['motDePasse']) < 7){
				$data['motDePasseErreur'] = 'Le mot de passe doit au moins 8 caracteres';
			}elseif(!preg_match($expReg, $data['motDePasse'])){
				$data['motDePasseErreur'] = 'Le mot de passe doit au moins contenir une lettre et un chiffre';
			}

			//Validation confirmation du mot de passe
			if(empty($data['confirmMotDePasse'])){
				$data['confirmMotDePasseErreur'] = 'Veuillez entrer un mot de passe';
			} elseif($data['motDePasse'] !== $data['confirmMotDePasse']){
				$data['confirmMotDePasseErreur'] = 'Veuillez entrer le meme mot de passe';
			}

			return $data;
		}


		private function cellier()
		{
			$bte = new Bouteille();
			$utilisateur = new Utilisateur();
			$cellier = new Cellier();

			$recherche = isset($_POST['tri']) ? trim($_POST['recherche_bouteille']) : "";
		    $critere    = isset($_POST['tri']) ? trim($_POST['typeTri']) : "nom";
			$sens       = isset($_POST['tri']) ? trim($_POST['ordre']) : "ASC";
			if(isset($_GET['id'])){
				$data = $bte->getListeBouteilleCellier($_SESSION['users_id'], $_GET['id'], $critere ,$sens,$recherche);
				
				//Permet d'aller chercher les infos du cellier du GET
				$cellierUnique = $cellier->getUnCellier($_GET['id']);
			}else{
				$idCellier = "";
				$data = $bte->getListeBouteilleCellier($_SESSION['users_id'],$idCellier,$critere ,$sens,$recherche);
			}
			

			//Créer un objet utilisateur pour aller chercher les celliers qui possede
		
			$celliers = $utilisateur->getCellierUtilisateur($_SESSION['users_id']);

			//initialise une variable $i
			$i = 1;

			include("vues/entete.php");
			include("vues/cellier.php");
			include("vues/pied.php");
				
		}
		

		private function listeBouteille()
		{
			$bte = new Bouteille();
			$cellier = $bte->getListeBouteilleCellier();
			//var_dump($cellier);
			
            echo json_encode($cellier);
                  
		}
		
		private function autocompleteBouteille()
		{
			$bte = new Bouteille();
			$body = json_decode(file_get_contents('php://input'));
            $listeBouteille = $bte->autocomplete($body->nom);
            
            echo json_encode($listeBouteille);
                  
		}
		private function ajouterNouvelleBouteilleCellier()
		{
			$body = json_decode(file_get_contents('php://input'));
			if(!empty($body)){
				$bte = new Bouteille();
				$resultat = $bte->ajouterBouteilleCellier($body);
				echo json_encode($resultat);
			}
			else{
				//Créer un objet utilisateur pour aller chercher les celliers qui possede
				$utilisateur = new Utilisateur();
				$celliers = $utilisateur->getCellierUtilisateur($_SESSION['users_id']);

				//initialise une variable $i
				$i = 1;
				include("vues/entete.php");
				include("vues/ajouter.php");
				include("vues/pied.php");
			}
			
            
		}
        /**
		 * fonction de modification des  bouteilles dans le cellier
		 * 
		 */

		private function modifierBouteilleCellier()
		{
			$body = json_decode(file_get_contents('php://input'));
			if(!empty($body)){
				$bte = new Bouteille();
				$resultat = $bte->modifierBouteilleCellier($body);
				echo json_encode($resultat);
			}
			else{
				$bte = new Bouteille();
				$data = $bte->getUneBouteilleCellier($_GET['id']);
				include("vues/entete.php");
				include("vues/modifier.php");
				include("vues/pied.php");
			} 
		}

		private function supprimerBouteilleCellier()
		{
			$body = json_decode(file_get_contents('php://input'));
			if(!empty($body)){
				$bte = new Bouteille();
				$resultat = $bte->supprimerBouteilleCellier($body);
				echo json_encode($resultat);
			}else{
				$bte = new Bouteille();
				$donnee = $bte->getUneBouteilleCellier($_GET['id']);
				include("vues/entete.php");
				include("vues/supprimer.php");
				include("vues/pied.php");
			}
			
			
		}
		/**
		 * fonction de boire une bouteiile
		 * STRATEGIE:retire un bouiteille dans le cellier
		 */
		private function boireBouteilleCellier()
		{
			$body = json_decode(file_get_contents('php://input'));
			
			$bte = new Bouteille();
			$resultat = $bte->modifierQuantiteBouteilleCellier($body->id, -1);
			echo json_encode($resultat);
		}

		private function ajouterBouteilleCellier()
		{
			$body = json_decode(file_get_contents('php://input'));
			
			$bte = new Bouteille();
			$resultat = $bte->modifierQuantiteBouteilleCellier($body->id, 1);
			echo json_encode($resultat);
			
		}

		//Fonction permetant d'ajouter un nouveau cellier a l'utilisateur
		private function ajouterNouveauCellier(){
			//Initialise array $data
			$data = [
				'retourAjouter' => ""
			];
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				$cellier = new Cellier();
				if($cellier->ajouterCellier($_SESSION['users_id'],$_POST['cellier__nom'])){
					//Redirige vers cellier
					header('Location: '. BASEURL .'?requete=cellier');
				}else{
					$data['retourAjouter'] = "Ajout non effectuée";
				}
			}
			include("vues/entete.php");
			include("vues/ajouterCellier.php");
			include("vues/pied.php");
		}		

		//Fonction permetant d'ajouter un nouveau cellier a l'utilisateur
		private function supprimerCellier(){
			//Initialise array $data
			$data = [
				'retour' => ""
			];

			$cellier = new Cellier();
			$donnee = $cellier->getUnCellier($_GET['id']);

			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				if($cellier->supprimerCellier($_GET['id'],$_SESSION['users_id'])){
					//Affiche un retour
					$data['retour'] = "Suppression effectuée";
					header('Location: '. BASEURL .'?requete=cellier');
				}else{
					//Affiche un retour erreur
					$data['retour'] = "Suppression non effectuée";
				}
			}
			include("vues/entete.php");
			include("vues/supprimerCellier.php");
			include("vues/pied.php");
		}	

		//Fonction permetant d'ajouter un nouveau cellier a l'utilisateur
		private function modifierCellier(){
			//Initialise array $data
			$data = [
				
				'retour' => ""
			];

			$cellier = new Cellier();
			$donnee = $cellier->getUnCellier($_GET['id']);
			
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				if($cellier->modifierCellier($_GET['id'], $_SESSION['users_id'], $_POST['cellier__nom'])){
					$data['retour'] = "Modification effectuée";
				}else{
					header("Refresh:0");
					$data['retour'] = "Modification non effectuée";
				}
			}
			include("vues/entete.php");
			include("vues/modifierCellier.php");
			include("vues/pied.php");
		}	

		//Fonction permetant de signaler une erreur
		private function signalerErreur(){
			$body = json_decode(file_get_contents('php://input'));
			$msg = new Messagerie();
			$resultat = $msg->ajouterMessage($body->texte, $_SESSION['users_id']);
			echo json_encode($resultat);
		}

		
		/*===================================SECTION ADMIN=============================================*/ 

		
		//Fonction permetant de verifier si l'utilisateur est un admin
		private function verificationAdmin(){

			//Permet de verifier si il est admin
			if($_SESSION['users_type'] !== "admin"){
				//Rederige vers la vue pour admin
				header('Location: '. BASEURL.'?requete=cellier');
			}
		}

		//Fonction permetant d'afficher la page d'accueil d'un admin
		private function admin(){

		

			//Permet d'avoir la liste des bouteilles
			$bte = new Bouteille();
			$data = $bte->getListeBouteille();

			//Permet d'avoir la liste des messages
			$msg = new Messagerie();
			$dataMsg = $msg->getListeMessage();
			
			
			//Permet d'initialiser un compteur
			$i = 1;

			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				$mot = $_POST['recherche_bouteille'];
				$critere = $_POST['typeTri'];
				$ordre = $_POST['ordre'];
				$limit = $_POST['limit'];
				$data = $bte->getListeBouteille($mot, $critere, $ordre, $limit);
			}

			include("vues/admin/entete.php");
			include("vues/admin/acceuil.php");
			include("vues/admin/pied.php");
		}

		//Fontion permetant a l'admin d"avoir la liste des utilisateurs
		private function adminUtilisateur(){
			//Permet d'avoir la liste des utilisateurs
			$utilisateur = new Utilisateur();
			$data = $utilisateur->getListeUtilisateur();

			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				$mot = $_POST['recherche_utilisateur'];
				$critere = $_POST['typeTri'];
				$ordre = $_POST['ordre'];
				$data =  $utilisateur->getListeUtilisateur($mot, $critere, $ordre);
			}

			include("vues/admin/entete.php");
			include("vues/admin/listeUtilisateur.php");
			include("vues/admin/pied.php");
		}

		//Fonction permetant de modifier une bouteille du catalogue
		private function adminModifierBouteille(){
			$body = json_decode(file_get_contents('php://input'));
			if(!empty($body)){
				$bte = new Bouteille();
				$resultat = $bte->modifierBouteilleCatalogue($body);
				echo json_encode($resultat);
			}
			else{
				$bte = new Bouteille();
				$data = $bte->getUneBouteilleCatalogue($_GET['id']);
				include("vues/admin/entete.php");
				include("vues/admin/modifierBouteille.php");
				include("vues/admin/pied.php");
			} 
			
		}

		private function ajouterDroitAdmin(){
			$body = json_decode(file_get_contents('php://input'));
			$utilisateur = new Utilisateur();
			$resultat = $utilisateur->ajouterDroitAdmin($body->id, $body->droit);
			echo json_encode($resultat);
			
		
		}
	

		//Fonction qui permet de supprimer un utilisateur
		private function supprimerUtilisateur(){
			$utilisateur = new Utilisateur();
			$data = $utilisateur->getUnUtilisateur($_GET['id']);
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				//Verifie si l'utilisateur veut supprimer
				if(isset($_POST['supprimerUtilisateur'])){
					$array = [
						'identifiant' => $_SESSION['users_login'],
						'motDePasse' => trim($_POST['ancienMotDePasseSupp']),
						'ancienMotDePasseErreurSupp'=>""
					];
					//Permet de confirmer le mot de passe de l'utilisateur avant la suppression
					if($utilisateur->controleUtilisateur($array)){
						if($utilisateur->supprimerUtilisateur($_GET['id'])){
							header('Location: '. BASEURL .'?requete=admin/utilisateur');
						}else{
							//Une erreur est sruvenue
						}
					}else{
						//Mauvais mot de passe
						$array['ancienMotDePasseErreurSupp'] = "Vous n'avez pas entrée le bon mot de passe";
					}
				}
			}
			include("vues/admin/entete.php");
			include("vues/admin/supprimerUtilisateur.php");
			include("vues/admin/pied.php");
		}

		//Fonction qui permet d'importer des bouteilles de la SAQ
		private function importationBouteille(){

			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				$saq = new SAQ();
				$data = Array();
				$page = $_POST['page'];
				$nombreProduit = $_POST['produit'];
				$type = $_POST['type'];

				for($i=0; $i<$page;$i++)	//permet d'importer séquentiellement plusieurs pages.
				{
					$data = array_merge($data, $saq->getProduits($nombreProduit,($i+1), $type));
				}

				//Permet de servir de compteur
				$i = 1;
			}
			include("vues/admin/entete.php");
			include("vues/admin/importation.php");
			include("vues/admin/pied.php");
		}

		//Fonction permetant de modifier une bouteille du catalogue
		private function statistiqueUtilisateur(){

			$utilisateur = new Utilisateur();
			$data = $utilisateur->getStatisiqueUtilisateur();
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				$tri = $_POST['tri'];
				$ordre =  $_POST['ordre'];
				if(isset($_POST['date'])){
					$date = $_POST['date'];
					$data = $utilisateur->getStatisiqueUtilisateur($tri, $ordre, $date);
				}else{
					$data = $utilisateur->getStatisiqueUtilisateur($tri, $ordre);
				}
				
			}
			include("vues/admin/entete.php");
			include("vues/admin/statistique.php");
			include("vues/admin/pied.php");
		}

		//Fonction permetant de supprimer un message
		private function supprimerMessage(){
			$body = json_decode(file_get_contents('php://input'));
			$msg = new Messagerie();
			$resultat = $msg->supprimerUnMessage($body->id);
			echo json_encode($resultat);
			
		}
			//================ ajout a liste d ' achat ==============================
			private function ajouterNouvelleBouteilleListe()
			{
				
				if (!isset($_SESSION)) {
					//initialiser la session
					session_start();
		
				}
				$body = json_decode(file_get_contents('php://input'));
				if (!empty($body)) {
				
					if (!isset($_SESSION['listeAchat'])) {
		
						$_SESSION['listeAchat'] = array();
					}
							$bte = new Bouteille();
						   $tab=array();
		
							$liste = new ListeAchat();
							$resultat =  $liste->addSession($body->id_bouteille,$bte->getUneBouteilleCatalogue($body->id_bouteille),$body->quantite, $_SESSION['users_id']);
						  
							echo json_encode($resultat);
				   
				} else {
				 //   $_SESSION['total']=0;
					$dataListe =$_SESSION['listeAchat'];
					//Créer un objet utilisateur pour aller chercher les celliers qui possede
					$utilisateur = new Utilisateur();
					$celliers = $utilisateur->getCellierUtilisateur($_SESSION['users_id']);
					
					//initialise une variable $i
					$i = 1;
					include "vues/entete.php";
					include "vues/ajouterListe.php";
					include "vues/pied.php";
				}
		
			}
			 //affichage de la liste
			 private function listeAchat()
			 {
				$dataListe =$_SESSION['listeAchat'];
				 include "vues/entete.php";
				 include "vues/listeAchat.php";
				 include "vues/pied.php";
			 }
			 /*  function qui supprime les bouteille de la liste d achat*/
		private function supprimerBouteilleListe(){
			//supprimer une bouteille
			unset($_SESSION['listeAchat'][$_GET['id']][$_SESSION['users_id']]);
			unset($_SESSION['quantite'][$_GET['id']]);
			$_SESSION['total']=count($_SESSION['total'])-1;
			$dataListe =$_SESSION['listeAchat'];
			include "vues/entete.php";
				include "vues/listeAchat.php";
				include "vues/pied.php";
			}

}
?>















