
<?php
/**
 * Partial: Welcome Button (Reproduction de .btn-primary en Tailwind)
 * Ce partial réutilise le design du bouton de connexion.
 * 
 * Variables attendues :
 *  - $url (string) : Lien de destination (ex: base_url('/login'))
 *  - $label (string) : Texte du bouton (ex: "Connexion")
 *  - $customClass (string, optionnel) : Classes supplémentaires ou overrides (ex: "px-[30px] py-[15px]")
 */

$url = $url ?? '#';
$label = $label ?? 'Button';
$padding = $padding ?? 'px-[28px] py-[14px]'; // Padding par défaut extrait
$customClass = $customClass ?? ''; 
$tag = $tag ?? 'a'; // 'a' ou 'button'
$type = $type ?? 'button'; // type pour le bouton (submit, button, reset)
$onclick = $onclick ?? '';

?>

<<?= $tag ?> 
   <?php if ($tag === 'a'): ?> href="<?= $url ?>" <?php else: ?> type="<?= $type ?>" <?php endif; ?>
   <?php if (!empty($onclick)): ?> onclick="<?= $onclick ?>" <?php endif; ?>
   class="inline-flex items-center justify-center gap-2 
          bg-primary text-white font-bold
          rounded transition-all duration-300
          hover:bg-accent hover:-translate-y-1 hover:shadow-lg
          <?= $padding ?> 
          <?= $customClass ?>">
    
    <span><?= $label ?></span>
</<?= $tag ?>>