<?php
namespace Cuisine\Wrappers;

class AttachmentMetabox extends Wrapper {

    /**
     * Return the igniter service key responsible for the Attachment class.
     * The key must be the same as the one used in the assigned
     * igniter service.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'attachment-metabox';
    }

}

?>