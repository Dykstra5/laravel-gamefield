export const isImage = function(attachment) {
    let mime = attachment.type || attachment.mime;
    mime = mime.split('/');

    if (mime[0] == 'image') {
        return true;
    }

    return false;
}