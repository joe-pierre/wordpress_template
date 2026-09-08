<?php
/**
 * Source data: "À propos" content and armoiries (Prompt 5, CONTENT_PROMPTS.md).
 *
 * Transcribed verbatim (pdftotext -layout) from
 * documents/Copie de Armoiries Diocèse de Ziguinchor.pdf — a real text-layer
 * PDF, no OCR needed. One typo in the source extraction ("eb source de vie"
 * in the "croix fleuries vertes" paragraph) is corrected to "en source de
 * vie" below; see DECISIONS.md.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return array{
 *     historique: array,
 *     armoiries_page: array,
 * }
 */
function dz_import_get_armoiries_data() {
	return array(

		// "Historique" page — the document's intro + 6-point "importance des
		// armoiries" text + conclusion. See DECISIONS.md for why "Historique"
		// (not a new "À propos du diocèse" page) was chosen as the target.
		'historique' => array(
			'source_id' => 'page-historique',
			'titre'     => __( 'Historique', 'diocese-ziguinchor' ),
			'content'   => '<p>Les armoiries d&rsquo;un dioc&egrave;se ne sont pas de simples ornements h&eacute;raldiques. Elles jouent un r&ocirc;le fondamental dans l&rsquo;identit&eacute; et la mission de l&rsquo;&Eacute;glise locale. Dans un monde o&ugrave; les dioc&egrave;ses doivent faire face &agrave; de nombreux d&eacute;fis &ndash; s&eacute;cularisation, perte des rep&egrave;res religieux, mondialisation et communication num&eacute;rique &ndash; ces symboles rev&ecirc;tent une signification encore plus profonde.</p>

<h3>1. Un Signe Visible d&rsquo;Appartenance et d&rsquo;Unit&eacute;</h3>
<p>Le dioc&egrave;se est une portion de l&rsquo;&Eacute;glise universelle confi&eacute;e &agrave; un &eacute;v&ecirc;que. Ses armoiries permettent aux fid&egrave;les de s&rsquo;identifier &agrave; leur &Eacute;glise locale et de se reconna&icirc;tre comme membres d&rsquo;une m&ecirc;me communaut&eacute;. Elles expriment la continuit&eacute; de la mission apostolique &agrave; travers les si&egrave;cles et renforcent le lien entre les paroisses et leur pasteur.</p>

<h3>2. Un T&eacute;moignage de l&rsquo;Histoire et des Racines du Dioc&egrave;se</h3>
<p>Chaque dioc&egrave;se est enracin&eacute; dans une histoire particuli&egrave;re, fa&ccedil;onn&eacute;e par les saints, les &eacute;v&eacute;nements et les traditions locales. Les armoiries rappellent ces racines et transmettent un patrimoine spirituel. Dans un monde en perp&eacute;tuel changement, elles permettent de ne pas oublier d&rsquo;o&ugrave; l&rsquo;on vient, ce qui fait notre identit&eacute; et ce que l&rsquo;on doit transmettre aux g&eacute;n&eacute;rations futures.</p>

<h3>3. Un Message Spirituel et Missionnaire</h3>
<p>Les symboles pr&eacute;sents dans les armoiries ne sont pas choisis au hasard&nbsp;: ils traduisent une mission. La couleur peut &eacute;voquer la Vierge Marie, une croix rappelle le sacrifice du Christ, une colombe symbolise l&rsquo;Esprit Saint&hellip; Ces signes visuels sont une cat&eacute;ch&egrave;se en eux-m&ecirc;mes et rappellent que l&rsquo;&Eacute;glise est envoy&eacute;e pour &eacute;vang&eacute;liser.</p>

<h3>4. Une Marque d&rsquo;Autorit&eacute; et de L&eacute;gitimit&eacute;</h3>
<p>L&rsquo;&eacute;v&ecirc;que, successeur des ap&ocirc;tres, porte lui-m&ecirc;me des armoiries qui sont souvent associ&eacute;es &agrave; celles du dioc&egrave;se. Cela exprime son r&ocirc;le de guide et de pasteur. &Agrave; une &eacute;poque o&ugrave; l&rsquo;autorit&eacute; eccl&eacute;siale est parfois contest&eacute;e, ces insignes rappellent que l&rsquo;&Eacute;glise s&rsquo;inscrit dans une tradition qui d&eacute;passe les individus et traverse les si&egrave;cles.</p>

<h3>5. Un Outil de Communication Moderne</h3>
<p>Dans le contexte actuel, o&ugrave; la visibilit&eacute; de l&rsquo;&Eacute;glise passe aussi par les m&eacute;dias, les r&eacute;seaux sociaux et les sites internet, les armoiries deviennent un &eacute;l&eacute;ment cl&eacute; de l&rsquo;identit&eacute; num&eacute;rique d&rsquo;un dioc&egrave;se. Toutes les grandes institutions de ce monde s&rsquo;identifient &agrave; travers leur logo ou leurs armoiries. C&rsquo;est dans ce cadre que les armoiries s&rsquo;inscrivent. Elles permettent une reconnaissance imm&eacute;diate et renforcent l&rsquo;image institutionnelle de l&rsquo;&Eacute;glise dans l&rsquo;espace public.</p>

<h3>6. Un Symbole d&rsquo;Ouverture et de Dialogue avec le Monde</h3>
<p>Les armoiries ne sont pas seulement un signe pour les catholiques. Elles permettent aussi aux non catholiques, non-croyants et aux autorit&eacute;s civiles de situer le dioc&egrave;se dans le paysage culturel et social. Elles t&eacute;moignent d&rsquo;une pr&eacute;sence chr&eacute;tienne qui dialogue avec son temps tout en restant fid&egrave;le &agrave; sa mission.</p>

<h3>Conclusion</h3>
<p>Les armoiries d&rsquo;un dioc&egrave;se ne sont pas de simples dessins, mais une v&eacute;ritable proclamation de foi, une proclamation d&rsquo;une identit&eacute; unique. Elles expriment une histoire, une mission et une appartenance. Dans un monde o&ugrave; l&rsquo;&Eacute;glise doit sans cesse r&eacute;affirmer son identit&eacute; et sa mission, elles sont un rep&egrave;re stable qui unit les fid&egrave;les et rappelle la pr&eacute;sence de Dieu au c&oelig;ur de la soci&eacute;t&eacute;.</p>',
		),

		// "Nos armoiries" dedicated page — symbol-by-symbol description,
		// rendered in alternating image/text rows by page-armoiries.php. All
		// rows reuse the same crest image (the page's featured image); see
		// DECISIONS.md.
		'armoiries_page' => array(
			'source_id' => 'page-armoiries',
			'titre'     => __( 'Nos armoiries', 'diocese-ziguinchor' ),
			'content'   => '<p>Chaque &eacute;l&eacute;ment des armoiries du dioc&egrave;se de Ziguinchor porte une signification propre. En voici la description, symbole par symbole.</p>',
			'image'     => 'assets/seed-images/armoiries/logo-diocese-ziguinchor.png',
			'symboles'  => array(
				array(
					'titre' => __( 'La croix dorée à double traverse', 'diocese-ziguinchor' ),
					'texte' => __( "La croix dorée à double traverse est un symbole d'autorité spirituelle élevée, propre à un diocèse ou à un évêque. Elle évoque également la mission apostolique de l'Église.", 'diocese-ziguinchor' ),
				),
				array(
					'titre' => __( 'La pirogue', 'diocese-ziguinchor' ),
					'texte' => __( "La pirogue représente l'Église, souvent décrite comme la « barque de Pierre » dans la tradition chrétienne, qui navigue à travers les tempêtes du monde guidée par le Christ. Elle symbolise également pour nous le moyen par lequel l'Évangile est parvenu à nos terres à travers les missionnaires.", 'diocese-ziguinchor' ),
				),
				array(
					'titre' => __( "La tiare épiscopale", 'diocese-ziguinchor' ),
					'texte' => __( "Coiffant la croix, elle représente l'autorité et la mission de l'évêque à guider le diocèse. Elle est ornée de joyaux symbolisant la richesse spirituelle.", 'diocese-ziguinchor' ),
				),
				array(
					'titre' => __( 'Le bouclier', 'diocese-ziguinchor' ),
					'texte' => __( "Symbole de la protection divine, de la foi et du combat spirituel, il représente pour nous la force de la foi qui anime tous les fidèles du diocèse de Ziguinchor. L'Église est souvent comparée à un rempart protecteur qui guide les fidèles et les garde dans la vérité. Comme bouclier collectif, elle protège ses enfants par les sacrements, l'enseignement et la prière. Il rappelle que Dieu nous protège, que notre foi nous défend contre le mal.", 'diocese-ziguinchor' ),
				),
				array(
					'titre' => __( "Forme de l'écu", 'diocese-ziguinchor' ),
					'texte' => __( "L'écu a une forme ogivale, typique des armoiries ecclésiastiques. Cela reflète une tradition de l'Église catholique, rappelant la structure des fenêtres gothiques, symbole d'élévation vers Dieu. La couleur bleu (azur) symbolise la paix, la fidélité, le ciel et la mer, pour rappeler les voies par lesquelles l'Évangile est entré dans notre terroir. Pour nous, elle symbolise la Vierge Marie, Immaculée Conception, patronne de notre diocèse.", 'diocese-ziguinchor' ),
				),
				array(
					'titre' => __( 'Les épis de riz', 'diocese-ziguinchor' ),
					'texte' => __( "Symbole de vie et de bénédiction, les épis de riz rappellent la richesse de la Casamance. Comme le riz qui germe et mûrit, l'annonce de l'Évangile en Casamance s'est enracinée dans un terrain humain et spirituel riche. Il rappelle également l'image du Christ comme grain de blé tombé en terre. L'abondance des épis symbolise également les nombreuses vocations sacerdotales et religieuses de notre terre, la Casamance ; ils symbolisent ainsi la fécondité de la mission de l'Église en Casamance.", 'diocese-ziguinchor' ),
				),
				array(
					'titre' => __( 'La colombe', 'diocese-ziguinchor' ),
					'texte' => __( "Placée au centre, elle symbolise le Saint-Esprit, la paix en Casamance, la réconciliation de la population et la présence Divine dans notre Église. Elle est symbole d'Espérance pour une paix durable.", 'diocese-ziguinchor' ),
				),
				array(
					'titre' => __( 'Les croix fleuries vertes sur le pourtour', 'diocese-ziguinchor' ),
					'texte' => __( "Ces croix représentent la mission d'évangélisation et l'espérance en la résurrection. Elles évoquent l'image de l'arbre de vie, la transformation de la souffrance en source de vie et de salut.", 'diocese-ziguinchor' ),
				),
				array(
					'titre' => __( "L'inscription latine", 'diocese-ziguinchor' ),
					'texte' => __( "L'inscription SIGILUM DIOECESIS ZIGUINCHORENSIS signifie « emblème » ou « sceau du Diocèse de Ziguinchor ».", 'diocese-ziguinchor' ),
				),
				array(
					'titre' => __( "L'année MCMLV", 'diocese-ziguinchor' ),
					'texte' => __( "L'année MCMLV, écrite en chiffres romains, indique l'année d'érection canonique de la Préfecture Apostolique de Ziguinchor en Diocèse.", 'diocese-ziguinchor' ),
				),
			),
		),

	);
}
