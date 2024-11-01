<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *

 *
 * @package    Tuxedo_Importer
 * @subpackage Tuxedo_Importer/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<svg class="tuxedo__logo" xmlns="http://www.w3.org/2000/svg" width="112" height="31" viewBox="0 0 112 31">
    <g fill="none" fill-rule="evenodd">
        <g fill="#1d2327">
            <g>
                <path d="M16.22 6v30.124H8.112V13.746H0V6h16.22zm7.18 20.989c2.514 0 4.56 2.029 4.56 4.523 0 2.494-2.046 4.523-4.56 4.523-2.516 0-4.562-2.03-4.562-4.523 0-2.494 2.046-4.523 4.561-4.523zm81.959-11.628c3.242 0 5.872 2.608 5.872 5.824 0 3.216-2.63 5.824-5.872 5.824-3.243 0-5.872-2.608-5.872-5.824 0-3.216 2.63-5.824 5.872-5.824zm-51.443.162v6.552c0 1.229.816 2.022 1.876 2.022s1.875-.793 1.875-2.022v-6.552h3.018v6.552c0 2.993-1.957 4.933-4.893 4.933s-4.893-1.94-4.893-4.933v-6.552h3.017zm12.535 0l1.794 2.912h.326l1.795-2.912h3.506l-3.506 5.5 3.75 5.824H70.61L68.57 23.61h-.326l-2.039 3.236H62.7l3.75-5.824-3.506-5.5h3.507zm18.035 0v2.75h-4.974v1.618h4.649v2.588h-4.649v1.618h4.974v2.75h-7.991V15.523h7.991zm7.328 0c3.18 0 5.627 2.427 5.627 5.662s-2.447 5.661-5.627 5.661h-4.322V15.523h4.322zm-43.58 0v2.912h-3.385v8.411h-3.017v-8.411h-3.384v-2.912h9.785zm-24.835 1.139c2.515 0 4.56 2.029 4.56 4.523 0 2.494-2.045 4.523-4.56 4.523-2.515 0-4.56-2.03-4.56-4.523 0-2.494 2.045-4.523 4.56-4.523zm81.96 1.577c-1.64 0-2.97 1.32-2.97 2.946 0 1.627 1.33 2.946 2.97 2.946 1.64 0 2.97-1.32 2.97-2.946 0-1.627-1.33-2.946-2.97-2.946zm-13.463.197H90.51v5.499h1.386c1.37 0 2.528-1.133 2.528-2.75s-1.159-2.75-2.528-2.75zM23.399 6.335c2.515 0 4.56 2.029 4.56 4.523 0 2.494-2.045 4.523-4.56 4.523-2.515 0-4.56-2.03-4.56-4.523 0-2.494 2.045-4.523 4.56-4.523z" transform="translate(-120 -46) translate(120 40)"></path>
            </g>
        </g>
    </g>
</svg>

<div class="tuxedo tuxedo__instructions">

  <h2>Importation automatique</h2>
  <p>L’importation automatique est programmée à minuit chaque jour. Dû aux limitations technologiques de Wordpress, l’importation sera seulement enclenchée à la première visite sur le site après minuit. L’importation se fera en arrière-plan, donc n’affectera pas le temps de chargement de cette première visite.</p>

  <p>La première importation sera longue mais les importations subséquentes seront plus rapides car seulement les spectacles et représentations qui ont été modifiés dans Tuxedo seront importés.</p>

  <h3>Désactiver l'importation automatique</h3>
  <pre>$timestamp = wp_next_scheduled(TUXEDO_IMPORTER_IMPORT_ACTION_NAME);
wp_unschedule_event($timestamp, TUXEDO_IMPORTER_IMPORT_ACTION_NAME);</pre>

  <h3>WP CLI</h3>
  <p>Vous pouvez utiliser WP CLI pour faire vos propres cédules d'importation automatique. Pour ce faire, il suffit d'utiliser la commande suivante: </p>
  <pre>wp tuxedo import</pre>

  <h2>Forcer l’importation</h2>
  <p>Il est possible de forcer une importation immédiate en utilisant le bouton «Forcer l'importation» dans la page principale du plugin. Forcer une importation ne va pas affecter l’importation automatique.</p>

  <h2>Résultats de l’importation</h2>
  <p>Les résultats de la dernière importation sont disponibles dès la fin de l’importation dans la page «Résultats».</p>
  <p>La page affiche la date et l'heure de début et de fin de l’importation, ainsi que le nombre de spectacles et de représentations affectés par chaque statut:</p>
  <ul>
  <li>Échecs (ce statut est présentement seulement utilisé lorsqu’il y a une erreur lors de l’importation de l’image)</li>
  <li>Spectacles/représentations modifiés (modifiés dans Wordpress après un changement dans Tuxedo)</li>
  <li>Spectacles/représentations créés</li>
  <li>Spectacles/représentations non modifiés (ignorés car ils n’ont pas été modifiés dans Tuxedo depuis la dernière importation ou car la date de la représentation est dans le passé)</li>
  <li>Spectacles/représentations supprimés (supprimés car la représentation est maintenant passée ou car le spectacle/représentation a été supprimé dans Tuxedo)</li>
  </ul>

  <h2>Suppression automatique</h2>
  <p>Les spectacles qui n’apparaissent plus dans l’API de Tuxedo, car ils ont été supprimés ou car ils ne font plus partie des canaux qui ont été sélectionnés dans les paramètres, seront supprimés automatiquement à la fin de l’importation.</p>

  <h2>Suppression manuelle</h2>
  <p>Il est aussi possible de supprimer le contenu manuellement. Plusieurs éléments peuvent être supprimés à la fois en utilisant les boutons dans la page principale du plugin, ou un à la fois en cliquant sur le bouton «Corbeille» dans la liste de spectacles ou des représentations.</p>

  <p>Il est important de ne pas oublier que les spectacles et les représentations supprimés manuellement seront réimportés durant la prochaine importation s'ils ne sont pas aussi supprimés dans Tuxedo ou retirés des canaux qui ont été sélectionnés dans les paramètres.</p>

  <p>Supprimer ou placer un spectacle dans la corbeille va automatiquement supprimer l’image qui a été importée de Tuxedo.</p>

  <h2>Modification manuelle</h2>
  <p>Il n’est pas possible de modifier le contenu des spectacles ou des représentations dans Wordpress.
  Mais il est possible (et recommandé) de visiter la page du spectacle dans Wordpress pour recadrer l’image pour assurer un affichage optimal.</p>

  <h2>Images</h2>
  <p>L’importation des images est optionnelle. Cocher la case «Télécharger les images?» dans les paramètres pour activer l’importation des images.</p>

  <p>Vous pouvez désactiver l’importation des images en décochant la case et utiliser le bouton «Supprimer toutes les photos importées» pour supprimer les images déjà importées.</p>

  <p>Il ne faut pas oublier que les fichiers des images vont être hébergés sur votre serveur.</p>

  <p>Dû aux limitations technologiques de Wordpress, il n'est pas possible de mettre les images importées par le plugin dans une section séparée des images que vous avez téléversées pour vos pages et vos articles. Mais les images importées par le plugin ont toutes le préfixe «tuxedo-image» donc vous pouvez facilement filtrer les images en utilisant le mot clef «tuxedo» ou «tuxedo-image» dans la recherche des images.</p>

  <p>Les gabarits utilisés par les shortcodes du plugin utilisent le nom du spectacle comme «alt» (texte alternatif) donc il n’est pas nécessaire d’en ajouter un sur l’image.</p>

  <h2>Paramètres</h2>
  <p>Les paramètres du plugin sont dans la page principale du plugin.</p>

  <ul>
  <li>Nom du compte
    <ul>
      <li>(obligatoire - information de connexion fournie par Tuxedo)</li>
    </ul>
  </li>
  <li>Nom d'utilisateur
    <ul>
      <li>(obligatoire - information de connexion fournie par Tuxedo)</li>
    </ul>
  </li>
  <li>Mot de passe
    <ul>
      <li>(obligatoire - information de connexion fournie par Tuxedo)</li>
    </ul>
  </li>
  <li>Canal/Canaux
    <ul>
      <li>(optionnel - ID des canaux séparés par des virgules)</li>
    </ul>
  </li>
  <li>Url de la billetterie
    <ul>
      <li>(obligatoire - nécessaire pour préfixer le lien vers la billetterie qui est fourni par l’API pour chaque spectacle et représentations. Example: https://hector-charland.tuxedobillet.com)</li>
    </ul>
  </li>
  <li>Télécharger les images?
    <ul>
      <li>(optionnel)</li>
    </ul>
  </li>
  <li>Créer un url pour chaque spectacle
    <ul>
      <li>(optionnel - pour chaque spectacle, une fiche va être créée. L'url sera «urldusite.com/spectacle/nomduspectacle».</li>
      <li>Vous pouvez trouver l'url pour un spectacle dans sa fiche dans <a href="/wp-admin/edit.php?post_type=tuxedo-show">la section Spectacles de Wordpress</a></li>
      <li>Si les liens ne fonctionnent pas vous devez re-sauvergarder <a href="/wp-admin/options-permalink.php">les paramètres des permaliens</a>. Vous n'avez pas besoin de changer la valeur, vous devez seulement cliquer sur «Enregistrer les modifications».</li>
    </ul>
  </li>
  <li>Utiliser le gabarit du plugin pour les spectacles
    <ul>
      <li>(Le gabarit du plugin est le shortcode [tuxedo-spectacle] avec les paramètres défaut. Si cette case n'est pas cochée vous devez créer un gabarit single-tuxedo-show.php dans votre thème)</li>
    </ul>
  </li>
</ul>


  <h2>Shortcodes</h2>
  <p>Nous recommandons <a href="https://kinsta.com/fr/blog/shortcodes-wordpress/" target="_blank">cet article</a> pour une explication des shortcodes en général et comment/où les utiliser.</p>

  <h3>[tuxedo-liste]</h3>
  <p>Exemple:</p>
  <p>[tuxedo-liste style="blocs" nombre="25" filtre_salle="Théâtre Hector-Charland" filtre_categorie="humour" soustitre="0" taille_image="thumbnail" lien="Acheter un billet"]</p>
  <img src="<?php echo plugin_dir_url(dirname(__FILE__)) . "/assets/tuxedo-liste.png";?>">
  <p>Paramètres: </p>
  <ul>
    <li>style
      <ul>
        <li>Détermine le CSS utilisé</li>
        <li>Valeurs possibles: <ul>
            <li>liste <strong>(valeur par défaut)</strong>
            </li>
            <li>blocs</li>
            <li>aucun - Aucun CSS ne sera ajouté, vous allez donc pouvoir utiliser le vôtre entièrement</li>
          </ul>
        </li>
      </ul>
    </li>
    <li>nombre
      <ul>
        <li>Nombre de spectacles affichés</li>
        <li>Valeurs possibles: <ul>
            <li>-1 <strong>(valeur par défaut) - </strong>tous les spectacles </li>
            <li>Chiffre de 1 à infini</li>
          </ul>
        </li>
      </ul>
    </li>
    <li>decalage
      <ul>
        <li>Nombre de spectacles sautés, fonctionne seulement si le paramètre nombre est utilisé</li>
        <li>Par exemple, si la valeur est 20, les 20 premiers spectacles vont etre sautés. Donc la liste va commencer avec le 21ème spectacles</li>
        <li>Donc si vous voulez séparer la liste des spectacles on plusieurs pages, vous pouvez utiliser le même shortcode sur plusieurs pages mais avec un paramètre decalage différent sur chaque page
        <ul>
            <li>Première page: nombre="20"</li>
            <li>Deuxième page: nombre="20" decalage="20"</li>
            <li>Troisième page: nombre="20" decalage="40"</li>
          </ul>
        </li>
        <li>Valeurs possibles: <ul>
            <li>0 <strong>(valeur par défaut)</strong> - aucun spectacle sauté </li>
            <li>Chiffre de 1 à infini</li>
          </ul>
        </li>
      </ul>
    </li>
    <li>filtre_salle
      <ul>
        <li>Filtre les résultats par le nom de la salle</li>
      </ul>
    </li>
    <li>filtre_categorie
      <ul>
        <li>Filtre les résultats par le nom d’une catégorie</li>
      </ul>
    </li>
    <li>filtre_categorie_rapport_1
      <ul>
        <li>Filtre les résultats par le nom d’une catégorie de rapport 1</li>
      </ul>
    </li>
    <li>filtre_categorie_rapport_2
      <ul>
        <li>Filtre les résultats par le nom d’une catégorie de rapport 2</li>
      </ul>
    </li>
    <li>filtre_categorie_rapport_3
      <ul>
        <li>Filtre les résultats par le nom d’une catégorie de rapport 3</li>
      </ul>
    </li>
    <li>filtre_etiquette
      <ul>
        <li>Filtre les résultats par l'étiquette</li>
      </ul>
    </li>
    <li>trier
      <ul>
        <li>Valeurs possibles: <ul>
            <li>date <strong>(valeur par défaut)</strong> - par date de la prochaine représentation </li>
            <li>a-z - par ordre alphabétique</li>
          </ul>
        </li>
      </ul>
    </li>
    <li>etiquette
      <ul>
        <li>Afficher ou non l'étiquette</li>
        <li>Valeurs possibles: <ul>
            <li>1 <strong>(valeur par défaut) </strong>- oui </li>
            <li>0 - non</li>
          </ul>
        </li>
      </ul>
    </li>
    <li>soustitre
      <ul>
        <li>Afficher ou non le sous-titre</li>
        <li>Valeurs possibles: <ul>
            <li>1 <strong>(valeur par défaut) </strong>- oui </li>
            <li>0 - non</li>
          </ul>
        </li>
      </ul>
    </li>
    <li>image
      <ul>
        <li>Afficher ou non l'image</li>
        <li>Valeurs possibles: <ul>
            <li>1 <strong>(valeur par défaut) </strong>- oui </li>
            <li>0 - non</li>
          </ul>
        </li>
      </ul>
    </li>
    <li>taille_image
      <ul>
        <li>La taille des images</li>
        <li>Valeurs possibles: <ul>
            <li>tuxedo-image-listing <strong>(valeur par défaut) </strong>- 300 x 200 pixels </li>
            <li>thumbnail - 150 x 150 pixels</li>
            <li>medium - maximum 300 x 300 pixels</li>
            <li>medium_large - 768 pixels de large</li>
            <li>large - maximum 1024 x 1024 pixels</li>
            <li>1536x1536 - maximum 1536 x 1536 pixels</li>
            <li>
              <div>
                <div>2048x2048 - maximum 2048 x 2048 pixels</div>
              </div>
            </li>
            <li>full - taille originale</li>
            <li>N'importe quelle taille d'image ajouté avec la fonction PHP add_image_size</li>
          </ul>
        </li>
      </ul>
    </li>
    <li>
      <div>
        <div>lien</div>
      </div>
      <ul>
        <li>Afficher ou non les liens vers la billetterie</li>
        <li>Valeurs possibles:
          <ul>
            <li>Réserver <strong>(valeur par défaut) </strong>- afficher avec «Réserver» comme texte du lien </li>
            <li>Texte du lien</li>
            <li>0 - non</li>
          </ul>
        </li>
      </ul>
    </li>
    <li>
      <div>
        <div>liens_internes</div>
      </div>
      <ul>
        <li>Utiliser le lien vers la billetterie ou vers l'url interne du spectacle (il est nécessaire de cocher la case «Créer un url pour chaque spectacle» dans les paramètres)</li>
        <li>Valeurs possibles:
          <ul>
            <li>0 <strong>(valeur par défaut) </strong>- Utiliser le lien vers la billetterie</li>
            <li>1 - Utiliser l'url interne du spectacle (il est nécessaire de cocher la case «Créer un url pour chaque spectacle» dans les paramètres)</li>
          </ul>
        </li>
      </ul>
    </li>
    <li>
      <div>
        <div>categories</div>
      </div>
      <ul>
        <li>Afficher ou non les catégories</li>
        <li>Valeurs possibles:
          <ul>
            <li>1 <strong>(valeur par défaut) </strong>- oui </li>
            <li>0 - non</li>
          </ul>
        </li>
      </ul>
    </li>
    <li>
      <div>
        <div>categorie_rapport_1</div>
      </div>
      <ul>
        <li>Afficher ou non les catégories de rapport 1</li>
        <li>Valeurs possibles:
          <ul>
            <li>1 - oui </li>
            <li>0 <strong>(valeur par défaut) </strong>- non</li>
          </ul>
        </li>
      </ul>
    </li>
    <li>
      <div>
        <div>categorie_rapport_2</div>
      </div>
      <ul>
        <li>Afficher ou non les catégories de rapport 2</li>
        <li>Valeurs possibles:
          <ul>
            <li>1 - oui </li>
            <li>0 <strong>(valeur par défaut) </strong>- non</li>
          </ul>
        </li>
      </ul>
    </li>
    <li>
      <div>
        <div>categorie_rapport_3</div>
      </div>
      <ul>
        <li>Afficher ou non les catégories de rapport 3</li>
        <li>Valeurs possibles:
          <ul>
            <li>1 - oui </li>
            <li>0 <strong>(valeur par défaut) </strong>- non</li>
          </ul>
        </li>
      </ul>
    </li>
    <li>
      <div>
        <div>prochaine_date</div>
      </div>
      <ul>
        <li>Afficher ou non la date de la prochaine représentation</li>
        <li>Valeurs possibles:
          <ul>
            <li>1 <strong>(valeur par défaut) </strong>- oui </li>
            <li>0 - non</li>
          </ul>
        </li>
      </ul>
    </li>
    <li>
      <div>
        <div>prochaine_salle</div>
      </div>
      <ul>
        <li>Afficher ou non la salle de la prochaine représentation</li>
        <li>Valeurs possibles:
          <ul>
            <li>1 <strong>(valeur par défaut) </strong>- oui </li>
            <li>0 - non</li>
          </ul>
        </li>
      </ul>
    </li>
  </ul>

  <h3>[tuxedo-spectacle]</h3>

  <p>Exemple:</p>
  <p>[tuxedo-spectacle spectacle="The Dark side of the moon" representations="2" soustitre="0" taille_image="2048x2048"]</p>
  <img src="<?php echo plugin_dir_url(dirname(__FILE__)) . "/assets/tuxedo-spectacle.png";?>">
  <p>Paramètres: </p>
  <ul>
      <li>style<ul>
          <li>Détermine si le CSS est utilisé</li>
          <li>Valeurs possibles: <ul>
              <li>1 <strong>(valeur par défaut)</strong> - oui</li>
              <li>0 - Aucun CSS ne sera ajouté, vous allez donc pouvoir utiliser le vôtre entièrement</li>
            </ul>
          </li>
        </ul>
      </li>
      <li>
        <div>
          <div>representations</div>
        </div>
        <ul>
          <li>Nombre de représentations affichées</li>
          <li>Valeurs possibles: <ul>
              <li>-1 <strong>(valeur par défaut) - </strong>toutes les représentations </li>
              <li>Chiffre de 1 à infini</li>
            </ul>
          </li>
        </ul>
      </li>
      <li>
        <div>
          <div>spectacle_id</div>
        </div>
        <ul>
          <li>ID du spectacle à afficher</li>
          <li>Il est nécessaire d'avoir spectacle_id ou spectacle</li>
        </ul>
      </li>
      <li>
        <div>
          <div>spectacle</div>
        </div>
        <ul>
          <li>Nom du spectacle à afficher</li>
          <li>Il est nécessaire d'avoir spectacle_id ou spectacle</li>
        </ul>
      </li>
      <li>etiquette
        <ul>
            <li>Afficher ou non l'étiquette</li>
            <li>Valeurs possibles: <ul>
                <li>1 <strong>(valeur par défaut) </strong>- oui </li>
                <li>0 - non</li>
            </ul>
            </li>
        </ul>
      </li>
      <li>
        <div>
          <div>duree</div>
        </div>
        <ul>
          <li>Afficher ou non la durée</li>
          <li>Valeurs possibles: <ul>
              <li>1 <strong>(valeur par défaut) </strong>- oui </li>
              <li>0 - non</li>
            </ul>
          </li>
        </ul>
      </li>
      <li>description <ul>
          <li>Afficher ou non la description</li>
          <li>Valeurs possibles: <ul>
              <li>1 <strong>(valeur par défaut) </strong>- oui </li>
              <li>0 - non</li>
            </ul>
          </li>
        </ul>
      </li>
      <li>distribution <ul>
          <li>Afficher ou non la distribution</li>
          <li>Valeurs possibles: <ul>
              <li>1 <strong>(valeur par défaut) </strong>- oui </li>
              <li>0 - non</li>
            </ul>
          </li>
        </ul>
      </li>
      <li>soustitre <ul>
          <li>Afficher ou non le sous-titre</li>
          <li>Valeurs possibles: <ul>
              <li>1 <strong>(valeur par défaut) </strong>- oui </li>
              <li>0 - non</li>
            </ul>
          </li>
        </ul>
      </li>
      <li>image <ul>
          <li>Afficher ou non l'image</li>
          <li>Valeurs possibles: <ul>
              <li>1 <strong>(valeur par défaut) </strong>- oui </li>
              <li>0 - non</li>
            </ul>
          </li>
        </ul>
      </li>
      <li>taille_image <ul>
          <li>La taille des images</li>
          <li>Valeurs possibles: <ul>
              <li>thumbnail - 150 x 150 pixels</li>
              <li>medium - maximum 300 x 300 pixels</li>
              <li>medium_large - 768 pixels de large</li>
              <li>large - <strong>(valeur par défaut) </strong>maximum 1024 x 1024 pixels </li>
              <li>1536x1536 - maximum 1536 x 1536 pixels</li>
              <li>
                <div>
                  <div>2048x2048 - maximum 2048 x 2048 pixels</div>
                </div>
              </li>
              <li>full - taille originale</li>
              <li>N'importe quelle taille d'image ajouté avec la fonction PHP add_image_size</li>
            </ul>
          </li>
        </ul>
      </li>
      <li>
        <div>
          <div>lien</div>
        </div>
        <ul>
          <li>Afficher ou non les liens vers la billetterie</li>
          <li>Valeurs possibles: <ul>
              <li>Réserver <strong>(valeur par défaut) </strong>- afficher avec «Réserver» comme texte du lien </li>
              <li>Texte du lien</li>
              <li>0 - non</li>
            </ul>
          </li>
        </ul>
      </li>
      <li>
        <div>
            <div>prix</div>
        </div>
        <ul>
            <li>Afficher ou non les prix de billet</li>
            <li>Valeurs possibles: <ul>
                <li>false <strong>(valeur par défaut)</strong></li>
                <li>true</li>
                </ul
            ></li>
        </ul>
      </li>
      <li>
        <div>
          <div>categories</div>
        </div>
        <ul>
          <li>Afficher ou non les catégories</li>
          <li>Valeurs possibles: <ul>
              <li>1 <strong>(valeur par défaut) </strong>- oui </li>
              <li>0 - non</li>
            </ul>
          </li>
        </ul>
      </li>
      <li>
        <div>
          <div>categorie_rapport_1</div>
        </div>
        <ul>
          <li>Afficher ou non les catégories de rapport 1</li>
          <li>Valeurs possibles: <ul>
              <li>1 - oui </li>
              <li>0 <strong>(valeur par défaut) </strong>- non</li>
            </ul>
          </li>
        </ul>
      </li>
      <li>
        <div>
          <div>categorie_rapport_2</div>
        </div>
        <ul>
          <li>Afficher ou non les catégories de rapport 2</li>
          <li>Valeurs possibles: <ul>
              <li>1 - oui </li>
              <li>0 <strong>(valeur par défaut) </strong>- non</li>
            </ul>
          </li>
        </ul>
      </li>
      <li>
        <div>
          <div>categorie_rapport_3</div>
        </div>
        <ul>
          <li>Afficher ou non les catégories de rapport 3</li>
          <li>Valeurs possibles: <ul>
              <li>1 - oui </li>
              <li>0 <strong>(valeur par défaut) </strong>- non</li>
            </ul>
          </li>
        </ul>
      </li>
      <li>
        <div>
          <div>prochaine_date</div>
        </div>
        <ul>
          <li>Afficher ou non la date de la prochaine représentation</li>
          <li>Valeurs possibles: <ul>
              <li>1 <strong>(valeur par défaut) </strong>- oui </li>
              <li>0 - non</li>
            </ul>
          </li>
        </ul>
      </li>
      <li>
        <div>
          <div>prochaine_salle</div>
        </div>
        <ul>
          <li>Afficher ou non la salle de la prochaine représentation</li>
          <li>Valeurs possibles: <ul>
              <li>1 <strong>(valeur par défaut) </strong>- oui </li>
              <li>0 - non</li>
            </ul>
          </li>
        </ul>
      </li>
      <li>
        <div>
            <div>langue</div>
        </div>
        <ul>
            <li>Choisir la langue dans laquelle afficher les informations du spectacle SI elle existe (dans le cas contraire, c'est la version française qui apparaitra)</li>
            <li>Valeurs possibles:
            <ul>
                <li>fr <strong>(valeur par défaut) </strong>- Français </li>
                <li>en - Anglais</li>
            </ul>
            </li>
        </ul>
      </li>
  </ul>
  <h2>Développement</h2>
  <p><strong>Il n'est pas recommandé</strong> de faire des changements dans le code du plugin car il est possible que le plugin soit mis à jour dans le futur.</p>
  <p>Si vous voulez personnaliser l’affichage des spectacles et des représentations s'affichent sur votre site vous pouvez ajouter du code directement dans les gabarits de votre thème ou vous pouvez créer vos propres shortcodes.</p>
  <p>Les spectacles sont sauvegardés dans le custom post type tuxedo-show, et les représentations dans le custom post type tuxedo-event. Il est donc possible d'utiliser <a href="https://developer.wordpress.org/reference/functions/get_posts/" target="_blank">get_posts</a> pour les obtenir.</p>
  <p>Le plugin <a href="https://www.advancedcustomfields.com/" target="_blank">Advanced Custom Fields (ACF)</a> est utilisé pour créer les champs des spectacles et des représentations, donc vous pouvez utiliser la fonction <a href="https://www.advancedcustomfields.com/resources/get_fields/" target="_blank">get_fields</a> pour obtenir la valeur de ces champs.</p>
  <p>Si vous avez besoin d'exemples, vous pouvez regarder le code dans les fichiers wp-content/plugins/wp-tuxedo-importer/includes/class-tuxedo-importer-shortcodes.php, wp-content/plugins/wp-tuxedo-importer/includes/templates/tuxedo-liste.php et wp-content/plugins/wp-tuxedo-importer/includes/templates/tuxedo-spectacle.php</p>

  <h3>Liste des champs ACF : </h3>
  <h4>Spectacles</h4>
  <ul>
    <li>show_title_en - Titre anglais</li>
    <li>show_subtitle - Sous-titre</li>
    <li>show_subtitle_en - Sous-titre anglais</li>
    <li>show_description - Description</li>
    <li>show_description_en - Description anglaise</li>
    <li>show_tag - Étiquette</li>
    <li>show_tag_en - Étiquette anglaise</li>
    <li>show_is_sold_out - Complet</li>
    <li>show_image_1 - Image 1</li>
    <li>show_image_2 - Image 2</li>
    <li>show_image_3 - Image 3</li>
    <li>show_image_4 - Image 4</li>
    <li>show_image_5 - Image 5</li>
    <li>show_image_6 - Image 6</li>
    <li>show_image_7 - Image 7</li>
    <li>show_image_8 - Image 8</li>
    <li>misc_value_1 - Misc. value 1</li>
    <li>misc_value_2 - Misc. value 2</li>
    <li>misc_value_3 - Misc. value 3</li>
    <li>show_distribution - Distribution</li>
    <li>show_distribution_en - Distribution anglaise</li>
    <li>show_duration - Durée</li>
    <li>show_link - Lien vers la billetterie</li>
    <li>show_categories - Catégories</li>
    <li>show_categories_en - Catégories anglaises</li>
    <li>show_report_categories_one - Catégories de rapport - 1</li>
    <li>show_report_categories_one_en - Catégories de rapport - 1 anglais</li>
    <li>show_report_categories_two - Catégories de rapport - 2</li>
    <li>show_report_categories_two_en - Catégories de rapport - 2 anglais</li>
    <li>show_report_categories_three - Catégories de rapport - 3</li>
    <li>show_report_categories_three_en - Catégories de rapport - 3 anglais</li>
    <li>show_external_link - Lien externe</li>
    <li>show_video_link - Lien vidéo</li>
    <li>tuxedo_id - ID Tuxedo</li>
    <li>show_modified_date - Date de modification</li>
    <li>show_last_seen - Dernier contact avec l'api</li>
    <li>show_next_event - Prochaine représentation</li>
    <li>show_next_event_date - Prochaine représentation - Date</li>
    <li>show_next_event_venue - Salle de la prochaine représentation</li>
  </ul>
  <h4>Représentations</h4>
  <ul>
    <li>event_show - Spectacle</li>
    <li>event_status - Status</li>
    <li>event_is_sold_out - Complet</li>
    <li>event_datetime - Date et heure</li>
    <li>event_venue - Salle</li>
    <li>event_link - Lien vers la billetterie</li>
    <li>event_price_categories - Prix des catégories</li>
    <li>tuxedo_id - ID Tuxedo</li>
    <li>event_modified_date - Date de modification</li>
    <li>event_last_seen - Dernier contact avec l'api</li>
  </ul>

  <h4>Exemple de code pour afficher les prix</h4>
  <pre>
&lt;?php $data = get_field('event_price_categories', $event_id);
foreach ($data as $row) : ?&gt;
    echo $row['category'] . ' - ' . $row['price'] . '&lt;br&gt;';
&lt;?php endforeach; ?&gt;</pre>

    <h4>Actions lors de l'importation</h4>
    <ul>
        <li>
            tuxedo_before_import (aucun argument) - Appelé avant l'importation
            <pre>add_action('tuxedo_before_import', 'ma_fonction');</pre>
        </li>
        <li>
            tuxedo_after_import (aucun argument) - Appelé après l'importation
            <pre>add_action('tuxedo_after_import', 'ma_fonction');</pre>
        </li>
        <li>
            tuxedo_after_show_import (int $post_id, stdClass $show) - Appelé après l'importation d'un spectacle
            L'argument $show contient les données du spectacle provenant de l'API
            <pre>add_action('tuxedo_after_show_import', 'ma_fonction', 10, 2);</pre>
        </li>
        <li>
            tuxedo_after_event_import (int $post_id, stdClass $event) - Appelé après l'importation d'une représentation
            L'argument $event contient les données de la représentation provenant de l'API
            <pre>add_action('tuxedo_after_event_import', 'ma_fonction', 10, 2);</pre>
        </li>
    </ul>
</div>