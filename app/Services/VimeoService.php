<?php

namespace App\Services;

use Vimeo\Exceptions\VimeoUploadException;
use Vimeo\Vimeo;

class VimeoService
{
    protected $vimeo;

    public function __construct(Vimeo $vimeo)
    {
        $this->vimeo = $vimeo;
    }

    /**
     * Upload video to Vimeo
     *
     * @param  string  $filePath  Full path to video file
     * @param  array  $params  Video metadata (name, description, etc)
     * @return array|false Returns video data or false on failure
     */
    public function upload($filePath, array $params = [])
    {
        try {
            // Prepare upload parameters
            $uploadParams = [
                'name' => $params['name'] ?? 'Untitled',
                'description' => $params['description'] ?? '',
            ];

            // Add privacy settings if provided
            if (isset($params['privacy'])) {
                $uploadParams['privacy'] = $params['privacy'];
            } else {
                $uploadParams['privacy'] = [
                    'view' => 'anybody',
                    'embed' => 'public',
                ];
            }

            // Add embed settings if provided
            if (isset($params['embed'])) {
                $uploadParams['embed'] = $params['embed'];
            }

            // Upload the video
            $uri = $this->vimeo->upload($filePath, $uploadParams);

            if (! $uri) {
                return false;
            }

            // Get video ID from URI (format: /videos/123456)
            $videoId = str_replace('/videos/', '', $uri);

            // Get video details
            $videoData = $this->vimeo->request($uri);

            if ($videoData['status'] === 200) {
                // Get the largest thumbnail
                $thumbnail = null;
                if (isset($videoData['body']['pictures']['sizes']) && ! empty($videoData['body']['pictures']['sizes'])) {
                    $sizes = $videoData['body']['pictures']['sizes'];
                    $thumbnail = end($sizes)['link'] ?? null;
                }

                return [
                    'id' => $videoId,
                    'uri' => $uri,
                    'link' => $videoData['body']['link'] ?? null,
                    'thumbnail' => $thumbnail,
                    'embed_html' => $videoData['body']['embed']['html'] ?? null,
                    'player_embed_url' => $videoData['body']['player_embed_url'] ?? null,
                    'duration' => $videoData['body']['duration'] ?? 0,
                    'width' => $videoData['body']['width'] ?? 0,
                    'height' => $videoData['body']['height'] ?? 0,
                ];
            }

            return false;
        } catch (VimeoUploadException $e) {
            \Log::error('Vimeo Upload Error: '.$e->getMessage());

            return false;
        } catch (\Exception $e) {
            \Log::error('Vimeo Error: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Delete video from Vimeo
     *
     * @param  string  $videoUri  Video URI (format: /videos/123456)
     * @return bool
     */
    public function delete($videoUri)
    {
        try {
            $response = $this->vimeo->request($videoUri, [], 'DELETE');

            return $response['status'] === 204;
        } catch (\Exception $e) {
            \Log::error('Vimeo Delete Error: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Update video details
     *
     * @param  string  $videoUri  Video URI
     * @param  array  $params  Parameters to update
     * @return bool
     */
    public function update($videoUri, array $params)
    {
        try {
            $response = $this->vimeo->request($videoUri, $params, 'PATCH');

            return $response['status'] === 200;
        } catch (\Exception $e) {
            \Log::error('Vimeo Update Error: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Get video details
     *
     * @param  string  $videoUri  Video URI
     * @return array|false
     */
    public function getVideo($videoUri)
    {
        try {
            $response = $this->vimeo->request($videoUri);

            if ($response['status'] === 200) {
                return $response['body'];
            }

            return false;
        } catch (\Exception $e) {
            \Log::error('Vimeo Get Video Error: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Set video thumbnail
     *
     * @param  string  $videoUri  Video URI
     * @param  string  $imagePath  Path to thumbnail image
     * @return bool
     */
    public function setThumbnail($videoUri, $imagePath)
    {
        try {
            $response = $this->vimeo->uploadImage($videoUri, $imagePath, true);

            return ! empty($response);
        } catch (\Exception $e) {
            \Log::error('Vimeo Thumbnail Error: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Get embed code for video
     *
     * @param  string  $videoId  Video ID (just the number)
     * @param  int  $width
     * @param  int  $height
     * @return string
     */
    public function getEmbedCode($videoId, $width = 640, $height = 360)
    {
        return sprintf(
            '<iframe src="https://player.vimeo.com/video/%s" width="%d" height="%d" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>',
            $videoId,
            $width,
            $height
        );
    }

    /**
     * Get video player URL
     *
     * @param  string  $videoId  Video ID
     * @return string
     */
    public function getPlayerUrl($videoId)
    {
        return "https://player.vimeo.com/video/{$videoId}";
    }

    /**
     * Update video embed domains whitelist
     *
     * @param  string  $videoUri  Video URI (e.g., /videos/123456)
     * @param  array  $domains  Array of allowed domains
     * @return bool
     */
    public function updateEmbedWhitelist($videoUri, array $domains)
    {
        try {
            $response = $this->vimeo->request($videoUri, [
                'privacy' => [
                    'embed' => 'whitelist',
                ],
                'embed' => [
                    'domains' => $domains,
                ],
            ], 'PATCH');

            return $response['status'] === 200;
        } catch (\Exception $e) {
            \Log::error('Vimeo Whitelist Update Error: '.$e->getMessage());

            return false;
        }
    }
}
