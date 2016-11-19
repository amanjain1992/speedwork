<?php

/*
 * This file is part of the Speedwork package.
 *
 * (c) Sankar <sankar.suda@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Turbo\Speedwork\Component\Media;

use Speedwork\Core\Controller as BaseController;
use Speedwork\Util\Str;

/**
 * @author Sankara Rao <sankar.suda@gmail.com>
 */
class Controller extends BaseController
{
    /**
     * Adding scripts beforerendering.
     *
     * @return [type] [description]
     */
    public function beforeRender()
    {
        $this->get('assets')->addScript(__DIR__.'/assets/script.js');
    }

    /**
     * Index Function.
     *
     * @return List of Url
     */
    public function index()
    {
        $source = $this->post('source');
        $source = str_replace(['./', '..'], '', $source);
        $source = trim(trim($source), '/');

        $sources      = [];
        $sources['/'] = 'Home';
        $path         = $this->app['path.media'];
        $media        = $this->app['location.media'];

        if ($source) {
            $sources[$source] = $source;
            $path .= $source.DS;
        }

        $files = [];
        foreach ($this->get('finder')->files()->name('/\.(gif|jpg|png)$/')->in($path) as $file) {
            $name    = $file->getFileName();
            $files[] = [
                'name' => $name,
                'path' => 'media/'.$name,
                'type' => 'image',
                'url'  => $media.$name,
            ];
        }

        return [
            'files'   => $files,
            'sources' => $sources,
        ];
    }

    public function upload()
    {
        $task = $this->input('task');

        if ($task == 'save') {
            $file = $_FILES['image'];

            if (!$file['name']) {
                $status['status']  = 'ERROR';
                $status['message'] = trans('Please choose a valid file');

                return $status;
            }

            $tmp_name = $file['tmp_name'];

            $ext = strtolower(strrchr($file['name'], '.'));

            $allowed = ['.jpg', '.png', '.gif', '.jpeg'];
            if (!in_array($ext, $allowed)) {
                $status['status']  = 'ERROR';
                $status['message'] = trans('File type not allowed. Allowed :0', [implode(',', $allowed)]);

                return $status;
            }

            $name      = Str::slug($file['name']);
            $name      = rtrim($name, $ext).uniqid().$ext;
            $directory = $this->app['path.media'];
            $filepath  = $directory.$name;

            if (!$this->get('files')->copy($tmp_name, $filepath)) {
                return [
                    'status'  => 'ERROR',
                    'message' => trans('An error occured. Please try again.'),
                ];
            }

            $media = $this->app['location.media'];

            return [
                'status' => 'OK',
                'files'  => [[
                    'name' => $name,
                    'type' => 'image',
                    'url'  => $media.$name,
                    'path' => 'media/'.$name,
                ]],
            ];
        }
    }
}
