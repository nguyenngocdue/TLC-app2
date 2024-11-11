<?php

namespace App\View\Components\Renderer;

use App\Models\User;
use App\Utils\ClassList;
use Illuminate\View\Component;

class Attachment2a extends Component
{
    public static $GALLERY_SUPPORTED_EXT = ['mp4', 'mov', 'png', 'jpeg', 'gif', 'jpg', 'pdf'];
    public static $TYPE = [
        "only_images" => [
            "title" => "Only Images (JPG, JPEG, PNG, GIF)",
            "array" => ["jpeg", "png", "jpg", "gif"],
            "string" => "jpeg,png,jpg,gif",
        ],
        "only_videos" => [
            "title" => "Only Videos (MP4)",
            "array" => ["mp4"],
            "string" => "mp4",
            // "string" => "video/mp4";
        ],
        "only_media" => [
            "title" => "Only Images (JPG, JPEG, PNG, GIF) and Videos (MP4)",
            "array" => ["jpeg", "png", "jpg", "gif", "mp4"],
            "string" => "jpeg,png,jpg,gif,mp4",
            // "string" => "video/* image/*";
        ],
        "only_non_media" => [
            "title" => "Only Non-Media (CSV, PDF, ZIP, DOCX)",
            "array" => ["csv", "pdf", "zip", "docx"],
            "string" => "csv,pdf,zip,docx",
        ],
        "all_supported" => [
            "title" => "All supported formats (JPG, JPEG, PNG, GIF, MP4, CSV, PDF, ZIP, DOCX)",
            "array" => ["jpeg", "png", "jpg", "gif", "mp4", "csv", "pdf", "zip", "docx"],
            "string" => "jpeg,png,jpg,gif,mp4,csv,pdf,zip,docx",
        ],
    ];

    private $attachments = [];

    public function __construct(
        private $name,
        private $value = "", //either a string of serialized array of attachments object or array or attachments 
        private $readOnly = false,
        private $destroyable = true,
        private $showUploadFile = true,
        private $label = '',
        private $properties = [],
        private $openType = 'gallery',
        // private $gridCols = 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 lg:gap-3 md:gap-2 sm:gap-1',
        private $groupMode = false,
        private $groupId = null,
        private $hiddenOrText = 'hidden',
        private $photoPerColumn = 5,
    ) {
        // dump($openType);
        if (is_array($value)) {
            $this->attachments = $value;
        } else {
            $this->attachments = (json_decode(htmlspecialchars_decode($value))) ?? [];
            //Convert to array if object given
            foreach ($this->attachments as &$attachment) if (is_object($attachment)) $attachment = (array)$attachment;
        }

        // if ($this->groupMode) $this->gridCols = "";

        // dump($this->attachments);
    }

    private function getMaxStr()
    {
        $MAX_FILE_COUNT = $this->properties['max_file_count'] ?? 10;
        $MAX_FILE_SIZE = $this->properties['max_file_size'] ?? 10;
        return "Max " . $MAX_FILE_COUNT . " files, each " . $MAX_FILE_SIZE  . "MB";
    }

    private function getButtonLabel($maxStr)
    {
        $ALLOWED_FILE_TYPE_KEY = $this->properties['allowed_file_types'] ?? 'only_images';
        if ($this->groupMode) {
            $message = "<i class='fa-sharp fa-regular fa-upload'></i>";
        } else {
            $message =  "<i class='fa-sharp fa-regular fa-upload'></i> Browse ($ALLOWED_FILE_TYPE_KEY) ($maxStr)";
        }

        return $message;
    }

    private function isSameEnv($attachment)
    {
        $attachmentFolder = $attachment['url_folder'] ?? '';

        $isProd = str_starts_with($attachmentFolder, 'app1_prod') || str_starts_with($attachmentFolder, 'app2_prod') || str_starts_with($attachmentFolder, 'avatars');
        $isTesting = str_starts_with($attachmentFolder, 'app2_beta');
        $isDev = !($isProd || $isTesting);

        $sameEnv = false;
        if (app()->isProduction() && $isProd) $sameEnv = true;
        if (app()->isTesting() && $isTesting) $sameEnv = true;
        if (app()->isLocal() && $isDev) $sameEnv = true;
        return $sameEnv;
    }

    private function enrichAttachments()
    {
        foreach ($this->attachments as &$attachment) {
            $attachment['isOrphan'] = isset($attachment['isOrphan']) && $attachment['isOrphan'];
            $attachment['borderColor'] = $attachment['isOrphan'] ? 'border-red-500' : 'border-gray-300';
            $attachment['showUrl'] = $this->properties['show_url'] ?? false;

            $attachment['sameEnv'] = $this->isSameEnv($attachment);
            $attachment['onClick'] = $this->openType == 'gallery' ? 'openGallery(' . $attachment['id'] . ')' : null;

            $user = User::findFromCache($attachment['owner_id'] ?? 1, ['getAvatar']);
            $src = $user->getAvatar->url_thumbnail ?? false;
            $src = $src ? app()->pathMinio() . $src : false;
            $attachment['uploader'] = [
                "id" => $user->id,
                "display_name" => $user->name,
                "first_name" => $user->first_name,
                "avatar_src" => $src,
            ];
        }
    }

    private function groupByType()
    {
        $docFiles = [];
        $imageFiles = [];
        $videoFiles = [];
        $unknownFiles = [];

        foreach ($this->attachments as $item) {
            $itemExtension = $item['extension'] ?? "";
            switch (true) {
                case in_array($itemExtension, self::$TYPE['only_images']['array']):
                    $imageFiles[] = $item;
                    break;
                case in_array($itemExtension, self::$TYPE['only_videos']['array']):
                    $videoFiles[] = $item;
                    break;
                case in_array($itemExtension, self::$TYPE['only_non_media']['array']):
                    $docFiles[] = $item;
                    break;
                default:
                    $unknownFiles[] = $item;
                    break;
            }
        }
        return [$docFiles, $imageFiles, $videoFiles, $unknownFiles];
    }

    public function render()
    {
        $HIDE_UPLOADER = $this->properties['hide_uploader'] ?? false;
        $HIDE_UPLOAD_DATE = $this->properties['hide_upload_date'] ?? false;
        $ALLOWED_FILE_TYPE_KEY = $this->properties['allowed_file_types'] ?? 'only_images';

        $acceptedExt = self::$TYPE[$ALLOWED_FILE_TYPE_KEY]['string'];
        $title = self::$TYPE[$ALLOWED_FILE_TYPE_KEY]['title'];

        $this->enrichAttachments();
        [$docFiles, $imageFiles, $videoFiles, $unknownFiles] = $this->groupByType();

        $maxString = $this->getMaxStr();

        return view('components.renderer.attachment2a.attachment2a', [
            'hiddenOrText' => $this->hiddenOrText,
            'name' => $this->name,
            'destroyable' => $this->destroyable,
            'readOnly' => $this->readOnly,
            'showUploadButton' => $this->showUploadFile,

            'path' => app()->pathMinio(),
            'attachments' => $this->attachments,
            'docs' => $docFiles,
            'images' => $imageFiles,
            'videos' => $videoFiles,
            'unknowns' => $unknownFiles,

            'acceptedExt' => $acceptedExt,
            'btnLabel' => $this->getButtonLabel($maxString),
            'btnTooltip' => $title . "\n" . $maxString,
            'btnClass' => ClassList::BUTTON,
            'thumbnailClass' => "relative flex flex-col items-center p1-025vw border border-2 rounded group/item overflow-hidden bg-inherit aspect-square ",

            'openType' => $this->openType, // gallery or href
            // 'gridCols' => $this->gridCols,
            'groupMode' => $this->groupMode,
            'groupId' => $this->groupId,
            'photoPerColumn' => $this->photoPerColumn,

            'hideUploader' => $HIDE_UPLOADER,
            'hideUploadDate' => $HIDE_UPLOAD_DATE,
        ]);
    }
}
