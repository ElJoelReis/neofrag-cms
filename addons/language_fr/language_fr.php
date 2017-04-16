<?php if (!defined('NEOFRAG_CMS')) exit;
/**************************************************************************
Copyright © 2015 Michaël BILCOT & Jérémy VALENTIN

This file is part of NeoFrag.

NeoFrag is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

NeoFrag is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class a_language_fr extends Language
{
	protected function __info()
	{
		return [
			'title'   => 'Français',
			'icon'    => '🇫🇷',
			'version' => '1.0',
			'depends' => [
				'neofrag' => 'Alpha 0.1.7'
			]
		];
	}

	public function locale()
	{
		return [
			'fr_FR.UTF8',
			'fr.UTF8',
			'fr_FR.UTF-8',
			'fr.UTF-8',
			'French_France.1252'
		];
	}

	public function date2sql(&$date)
	{
		if (preg_match('#^(\d{2})/(\d{2})/(\d{4})$#', $date, $match))
		{
			$date = $match[3].'-'.$match[2].'-'.$match[1];
		}
	}

	public function time2sql(&$time)
	{
		if (preg_match('#^(\d{2}):(\d{2})$#', $time, $match))
		{
			$time = $match[1].':'.$match[2].':00';
		}
	}

	public function datetime2sql(&$datetime)
	{
		if (preg_match('#^(\d{2})/(\d{2})/(\d{4}) (\d{2}):(\d{2})$#', $datetime, $match))
		{
			$datetime = $match[3].'-'.$match[2].'-'.$match[1].' '.$match[4].':'.$match[5].':00';
		}
	}

	public function i18n()
	{
		return [
			'about'                          => 'À propos',
			'access_path_already_used'       => 'Chemin d\'accès déjà utilisé',
			'activate'                       => 'Activer',
			'add'                            => 'Ajouter',
			'addons'                         => 'Gestion des composants',
			'administration'                 => 'Administration',
			'age'                            => '(%d an)|(%d ans)',
			'ambiguities'                    => 'Ambiguïtés',
			'authorized_members'             => 'Membres autorisés',
			'avatar'                         => 'Avatar',
			'avatar_must_be_square'          => 'L\'avatar doit être carré',
			'avatar_size_error'              => 'L\'avatar doit faire au moins %dpx',
			'back'                           => 'Retour',
			'background'                     => 'Image de fond',
			'background_color'               => 'Couleur de fond',
			'background_repeat'              => 'Répéter l\'image',
			'birth_date'                     => 'Date de naissance',
			'both'                           => 'Les deux',
			'bottom'                         => 'Bas',
			'cancel'                         => 'Annuler',
			'center'                         => 'Centré',
			'close'                          => 'Fermer',
			'col'                            => 'Col',
			'col_delete_message'             => 'Êtes-vous sûr(e) de vouloir supprimer cette <b>colonne</b> ?<br />Tous les <b>widgets</b> contenus seront également supprimés.',
			'color'                          => 'Couleur',
			'comeback_common_layout'         => 'Revenir à la disposition commune',
			'comeback_common_layout_message' => 'Êtes-vous sûr(e) de vouloir revenir à la disposition commune ?<br />Toutes les <b>colonnes</b> et <b>widgets</b> associés à cette zone seront perdus.',
			'coming_soon'                    => 'Réouverture prévue dans',
			'comment_unlogged'               => 'Vous devez être identifié pour pouvoir poster un commentaire',
			'comments'                       => '%d Commentaire|%d Commentaires',
			'common_layout'                  => 'Disposition commune',
			'configuration'                  => 'Configuration',
			'configure'                      => 'Configurer',
			'content'                        => 'Contenu',
			'continue'                       => 'Continuer',
			'copyright_all_rights_reserved'  => 'Copyright © '.date('Y').' - '.$this->config->nf_name.' tous droits réservés',
			'create_account'                 => 'Créer un compte',
			'custom_layout'                  => 'Disposition spécifique à la page',
			'dashboard'                      => 'Tableau de bord',
			'database'                       => 'Base de données',
			'date_long'                      => '%A %e %B %Y',
			'date_short'                     => '%d/%m/%Y',
			'date_time_long'                 => '%A %e %B %Y, %H:%M',
			'date_time_short'                => '%d/%m/%Y %H:%M',
			'day_at'                         => '%s, à %s',
			'default_theme'                  => 'Thème de base',
			'delete_confirmation'            => 'Confirmation de suppression',
			'design'                         => 'Apparence',
			'edit'                           => 'Éditer',
			'email_unavailable'              => 'Addresse email déjà utilisée',
			'error'                          => 'Erreur',
			'error_theme_install'            => 'Le thème n\'a pas pu être installé, veuillez vérifier qu\'il s\'agisse bien d\'un thème',
			'female'                         => 'Femme',
			'file_icon'                      => ' d\'image (format carré min. %dpx et max. %d Mo)',
			'file_picture'                   => ' d\'image (max. %d Mo)',
			'file_transfer_error'            => 'Erreur de transfert',
			'file_transfer_error_1'          => 'La taille du fichier téléchargé excède la valeur de upload_max_filesize, configurée dans le php.ini',
			'file_transfer_error_2'          => 'La taille du fichier téléchargé excède la valeur de MAX_FILE_SIZE, qui a été spécifiée dans le formulaire HTML',
			'file_transfer_error_3'          => 'Le fichier n\'a été que partiellement téléchargé',
			'file_transfer_error_4'          => 'Aucun fichier n\'a été téléchargé',
			'file_transfer_error_6'          => 'Un dossier temporaire est manquant',
			'file_transfer_error_7'          => 'Échec de l\'écriture du fichier sur le disque',
			'file_transfer_error_8'          => 'Une extension PHP a arrêté l\'envoi de fichier',
			'first_name'                     => 'Prénom',
			'forbidden_guests'               => 'Visiteurs exclus',
			'forbidden_members'              => 'Membres exclus',
			'forum'                          => 'Forum',
			'gender'                         => 'Sexe',
			'group_admins'                   => 'Administrateurs',
			'group_members'                  => 'Membres',
			'group_visitors'                 => 'Visiteurs',
			'groups'                         => 'Groupes',
			'guest'                          => 'Visiteur',
			'help'                           => 'Aide',
			'hide'                           => 'Masquer',
			'home'                           => 'Accueil',
			'horizontally'                   => 'Horizontalement',
			'hours_ago'                      => 'Il y a environ une heure|Il y a %d heures',
			'icon'                           => 'Icône',
			'icon_must_be_square'            => 'L\'icône doit être carré',
			'icon_size_error'                => 'L\'icône doit faire au moins %dpx',
			'icons'                          => '{2} icônes',
			'increase'                       => 'Augmenter',
			'install'                        => 'Installer',
			'install_in_progress'            => 'Installation du thème...',
			'install_theme'                  => 'Installation / Mise à jour d\'un thème',
			'invalid_birth_date'             => 'Vraiment ?! 2.1 Gigowatt !',
			'invalid_values'                 => 'La valeur sélectionnée n\'est pas valide|Les valeurs sélectionnées ne sont pas valides',
			'invalide_filetype'              => 'Type de ressource non géré',
			'ip_address'                     => 'Adresse IP',
			'last_activity'                  => 'Dernière activité',
			'last_name'                      => 'Nom',
			'left'                           => 'Gauche',
			'loading'                        => 'Chargement en cours...',
			'location'                       => 'Localisation',
			'login'                          => 'Connexion',
			'login_title'                    => 'Se connecter',
			'maintenance'                    => 'Maintenance',
			'male'                           => 'Homme',
			'manage_my_account'              => 'Gérer mon compte',
			'member'                         => 'Membre',
			'members'                        => 'Membres',
			'message_needed'                 => 'Veuillez remplir un message',
			'middle'                         => 'Milieu',
			'minutes_ago'                    => 'Il y a environ une minute|Il y a %d minutes',
			'moderation'                     => 'Modération',
			'module'                         => 'Module',
			'my_account'                     => 'Mon espace',
			'my_comment'                     => 'Mon commentaire',
			'name'                           => 'Nom',
			'navigation'                     => 'Navigation',
			'new_col'                        => 'Nouveau Col',
			'new_row'                        => 'Nouveau Row',
			'new_widget'                     => 'Nouveau Widget',
			'no'                             => 'Non',
			'no_data'                        => 'Il n\'y a rien ici pour le moment',
			'no_result'                      => 'Aucun résultat ne correspond à la recherche',
			'notifications'                  => 'Notifications',
			'now'                            => 'À l\'instant',
			'online'                         => 'en ligne',
			'open_website'                   => 'Ouvrir le site',
			'our_website_is_unavailable'     => 'Notre site est momentanément indisponible,<br />nous vous invitons à revenir dans un instant...',
			'pages'                          => '{0} sur {1} pages',
			'password'                       => 'Mot de passe',
			'permissions'                    => 'Permissions',
			'permissions_reset_comfirmation' => 'Confirmation de réinitialisation des permissions',
			'permissions_reset_message'      => 'Êtes-vous sûr(e) de vouloir réinitialiser les permissions ?',
			'position'                       => 'Position',
			'quote'                          => 'Citation',
			'reduce'                         => 'Réduire',
			'reinstall'                      => 'Réinstaller',
			'reinstall_to_default'           => 'Réinstaller par défaut',
			'remove'                         => 'Supprimer',
			'remove_file'                    => 'Supprimer le fichier ?',
			'removed_message'                => 'Message supprimé',
			'reply'                          => 'Répondre',
			'required_fields'                => '* Toutes les informations marquées d\'une étoile sont requises',
			'required_input'                 => 'Veuillez remplir ce champ',
			'reset'                          => 'Réinitialiser',
			'reset_automatic'                => 'Remettre en automatique',
			'results'                        => '%d résultat|%d résultats',
			'results_total'                  => ' sur %d au total',
			'right'                          => 'Droite',
			'row'                            => 'Row',
			'row_delete_message'             => 'Êtes-vous sûr(e) de vouloir supprimer cette <b>ligne</b> ?<br />Toutes les <b>colonnes</b> et <b>widgets</b> contenus seront également supprimés.',
			'row_design'                     => 'Apparence de la ligne',
			'save'                           => 'Valider',
			'search'                         => 'Rechercher',
			'search...'                      => 'Rechercher...',
			'seconds_ago'                    => 'Il y a une seconde|Il y a %d secondes',
			'select_image_file'              => 'Veuiller choisir un fichier d\'image',
			'send'                           => 'Envoyer',
			'server'                         => 'Serveur',
			'sessions'                       => 'Sessions',
			'settings'                       => 'Paramètres',
			'show_all'                       => 'Tout afficher',
			'signature'                      => 'Signature',
			'sort'                           => 'Ordonner',
			'theme_activation'               => 'Activation du thème',
			'theme_activation_message'       => 'Êtes-vous sûr(e) de vouloir activer le thème <b>\'+$(this).data(\'title\')+\'</b> ?',
			'theme_deletion_message'         => 'Êtes-vous sûr(e) de vouloir supprimer définitivement le thème <b>\'+$(this).parents(\'.thumbnail:first\').data(\'title\')+\'</b> ?',
			'theme_reinstallation_message'   => 'Êtes-vous sûr(e) de vouloir réinstaller le thème <b>\'+$(this).parents(\'.thumbnail:first\').data(\'title\')+\'</b> ?<br />Toutes les dispositions et configurations de widgets seront perdues.',
			'themes'                         => 'Thèmes',
			'time_long'                      => '%H:%M:%S',
			'time_short'                     => '%H:%M',
			'title'                          => 'Titre',
			'top'                            => 'Haut',
			'unavailable_feature'            => 'Cette fonctionnalité n\'est pas disponible pour l\'instant',
			'unfound_translation'            => '{0}Traduction introuvable : %s|{1}Erreur de pluralisation %s',
			'unknown_method'                 => 'Methode inexistante %s::%s',
			'unknown_property'               => 'Propriété inexistante %s::%s',
			'upload_file'                    => 'Télécharger un fichier',
			'username'                       => 'Identifiant',
			'username_unavailable'           => 'Identifiant déjà utilisé',
			'users'                          => 'Utilisateurs',
			'vertically'                     => 'Verticalement',
			'view_my_profile'                => 'Voir mon profil',
			'website'                        => 'Site web',
			'website_down_for_maintenance'   => 'Site en opération de maintenance',
			'website_under_maintenance'      => 'Site en maintenance',
			'widget_delete_message'          => 'Êtes-vous sûr(e) de vouloir supprimer ce <b>widget</b> ?',
			'widget_design'                  => 'Apparence du Widget',
			'widget_settings'                => 'Configuration du Widget',
			'wrong_email'                    => 'Veuillez entrer une adresse email valide',
			'wrong_url'                      => 'Veuillez entrer une adresse url valide',
			'yesterday_at'                   => 'Hier, à %s',
			'your_response'                  => 'Votre réponse',
			'zone'                           => 'Zone #%d'
		];
	}
}

/*
NeoFrag Alpha 0.1.7
./addons/language_fr/language_fr.php
*/