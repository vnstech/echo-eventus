<?php

namespace App\Services;

use Core\Constants\Constants;
use App\Models\Event;

class EventAvatar
{
    /** @var array<string, mixed> $image */
    private array $image;

    public function __construct(
        private Event $model,
        /** @var array<string, string|array<string>|int> $validations */
        private array $validations = []
    ) {
    }

    public function path(): string
    {
        if ($this->model->avatar_name) {
            $fullPath = $this->getAbsoluteSavedFilePath();

            if (file_exists($fullPath)) {
                $hash = md5_file($fullPath);
                return $this->baseDir() . $this->model->avatar_name . "?" . $hash;
            }
        }

        return "/assets/images/defaults/avatar.png";
    }

    /**
     * @param array<string, mixed> $image
     */
    public function update(array $image): bool
    {
        $this->image = $image;

        if (!$this->isValidImage()) {
            return false;
        }

        return $this->updateFile();
    }

    protected function updateFile(): bool
    {
        if (empty($this->getTmpFilePath())) {
            return false;
        }

        $this->removeOldImage();
        $this->model->update(['avatar_name' => $this->getFileName()]);

        $resp = move_uploaded_file(
            $this->getTmpFilePath(),
            $this->getAbsoluteDestinationPath()
        );

        if (!$resp) {
            $error = error_get_last();
            throw new \RuntimeException(
                'Failed to move uploaded file: ' . ($error['message'] ?? 'Unknown error')
            );
        }

        return true;
    }

    private function getTmpFilePath(): string
    {
        return $this->image['tmp_name'];
    }

    private function removeOldImage(): void
    {
        if ($this->model->avatar_name) {
            unlink($this->getAbsoluteSavedFilePath());
        }
    }

    private function getFileName(): string
    {
        $file_name_splitted  = explode('.', $this->image['name']);
        $file_extension = end($file_name_splitted);
        return 'avatar.' . $file_extension;
    }

    private function getAbsoluteDestinationPath(): string
    {
        return $this->storeDir() . $this->getFileName();
    }

    private function getAbsoluteSavedFilePath(): string
    {
        return Constants::rootPath()->join('public' . $this->baseDir())->join($this->model->avatar_name);
    }

    public function baseDir(): string
    {
        return "/assets/uploads/{$this->model::table()}/{$this->model->id}/";
    }

    private function storeDir(): string
    {
        $path = Constants::rootPath()->join('public' . $this->baseDir());
        if (!is_dir($path)) {
            mkdir(directory: $path, recursive: true);
        }

        return $path;
    }

    private function isValidImage(): bool
    {
        if (isset($this->validations['extension'])) {
            $this->validateImageExtension();
        }

        if (isset($this->validations['size'])) {
            $this->validateImageSize();
        }

        return $this->model->errors('avatar_name') === null;
    }

    private function validateImageExtension(): void
    {
        $file_name_splitted  = explode('.', $this->image['name']);
        $file_extension = end($file_name_splitted);

        if (!in_array($file_extension, $this->validations['extension'])) {
            $this->model->addError('avatar_name', 'Image format not supported!');
        }
    }

    private function validateImageSize(): void
    {
        if ($this->image['size'] > $this->validations['size']) {
            $this->model->addError('avatar_name', 'The file must be a maximum of 2MB!');
        }
    }
}
