<?php
// Testons si le fichier a bien été envoyé et s'il n'y a pas d'erreur
function FileUploader(string $file_name): string
{
    if (
        isset($_FILES[$file_name]) and $_FILES[$file_name]['error']
        == 0
    ) {
        // Testons si le fichier n'est pas trop gros
        if ($_FILES[$file_name]['size'] <= 1000000) {
            // Testons si l'extension est autorisée
            $infosfichier =
                pathinfo($_FILES[$file_name]['name']);
            $extension_upload = $infosfichier['extension'];
            $extensions_autorisees = array(
                'jpg',
                'jpeg',
                'gif',
                'png'
            );
            if (in_array(
                $extension_upload,
                $extensions_autorisees
            )) {
                // On peut valider le fichier et le stocker définitivement
                move_uploaded_file($_FILES[$file_name]['tmp_name'], 'uploads/' .
                    basename($_FILES[$file_name]['name']));
                echo "L'envoi a bien été effectué !";
                return 'uploads/' .
                    basename($_FILES[$file_name]['name']);
            }
        }
    }
    return "";
}
