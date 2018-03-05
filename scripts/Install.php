<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Script;

use Eureka\Eurekon;

/**
 * Install class.
 * Architecture:
 *
 * Code Source:
 *   {PATH}/dev/[dev|devprod|preprod].{domain}[_v{VERSION}]/composer_{devprod|preprod|prod}.json
 * Media Source:
 *   {PATH]/dev/media/[images|videos]/{hexa1}/{hexa1}/{hexa1}/{hexa32}.{ext}
 *
 * Symlinks (for apache config document root)
 *   {PATH}/www/[dev|devprod|preprod].{domain}
 *   {PATH}/www/media
 *
 * @author  Romain Cottard
 */
class Install extends Eurekon\AbstractScript
{
    /** @var string $env Environment */
    private $env = '';

    /** @var string $version Prod version */
    private $version = '';

    /** @var string $root Root directory */
    private $root = '';

    /** @var string $domain Domain name */
    private $domain = '';

    /**
     * Install constructor.
     */
    public function __construct()
    {
        $this->setExecutable();
        $this->setDescription('Install script...');
    }

    /**
     * {@inheritdoc}
     */
    public function help()
    {
        $style = new Eurekon\Style\Style(' *** RUN - HELP ***');
        Eurekon\IO\Out::std($style->colorForeground(Eurekon\Style\Color::GREEN)->get());
        Eurekon\IO\Out::std('');

        $help = new Eurekon\Help('...');
        $help->addArgument('', 'domain', 'Domain name: example.com', true, true);
        $help->addArgument('', 'env', 'Environment (devprod, preprod or prod)', true, true);
        $help->addArgument('', 'version', 'Version (x.y.z)', true, true);
        $help->display();
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $argument = Eurekon\Argument\Argument::getInstance();

        if ($argument->has('h', 'help')) {
            $this->help();
            return ;
        }

        $this->env     = $argument->get('env');
        $this->version = $argument->get('version', null, '');
        $this->domain  = $argument->get('domain', null, '');

        if (empty($this->domain)) {
            throw new \RuntimeException('Empty domain!');
        }

        switch ($this->env) {
            case 'dev':
            case 'local':
                break;
            case 'preprod':
            case 'prod':
                if (empty($this->version)) {
                    throw new \Exception('Empty version for preprod/prod env!');
                }
                break;
            default:
                throw new \Exception('Invalid environment !');
        }

        $this->root = realpath(__DIR__ . '/..');

        $this->fixCachePerms();
        //$this->linkMedia();
        $this->linkSite();
    }

    /**
     * Fix cache perms.
     *
     * @return void
     * @throws \Exception
     */
    private function fixCachePerms()
    {
        $pathList = [
            '/.cache'           => 0777,
            '/.cache/tpl'       => 0777,
            '/web/thumbnail'    => 0777,
            '/web/static/cache' => 0777,
        ];

        foreach ($pathList as $path => $mode) {
            $fullPath = $this->root . $path;

            $this->out(' . Fix "' . $fullPath . '" directory');
            if (!is_dir($fullPath) && !mkdir($fullPath, $mode, true)) {
                throw new \Exception('Cannot create "' . $fullPath . '" directory !');
            }

            if (!chmod($fullPath, $mode)) {
                throw new \Exception('Cannot fix permissions for .cache/ dir!');
            }
        }
    }

    /**
     * Create media symlink.
     *
     * @return void
     * @throws \Exception
     */
    private function linkMedia()
    {
        $this->out(' . Create symlink to media directory');

        $mediaDir  = realpath($this->root . '/../media');
        $staticDir = $this->root . '/web/static/media';

        exec('ln -s ' . escapeshellarg($mediaDir) . ' ' . escapeshellarg($staticDir));
    }

    /**
     * Create media symlink.
     *
     * @return void
     * @throws \Exception
     */
    private function linkSite()
    {
        $this->out(' . Create symlink to site (for apache)');

        switch ($this->env) {
            case 'devprod':
                $site = 'devprod-' . $this->domain;
                $link = $site;
                break;
            case 'preprod':
                $site = 'preprod-' . $this->domain . '_v' . $this->version;
                $link = 'preprod-' . $this->domain;
                break;
            case 'prod':
                $site = $this->domain . '_v' . $this->version;
                $link = $this->domain;
                break;
            case 'local':
                $site = 'local-' . $this->domain;
                $link = $site;
                break;
            case 'dev':
            default:
                $site = 'dev-' . $this->domain;
                $link = $site;
                break;
        }

        $sourceDir = realpath($this->root . '/../../dev/') . '/' . $site;
        $targetDir = realpath($this->root . '/../../www/') . '/' . $link;

        if (file_exists($targetDir) && !unlink($targetDir)) {
            throw new \Exception('Cannot remove old link!');
        }

        exec('ln -s ' . escapeshellarg($sourceDir) . ' ' . escapeshellarg($targetDir));
    }

    /**
     * @param  string $text
     * @return void
     */
    private function out($text)
    {
        Eurekon\IO\Out::std((string) (new Eurekon\Style\Style($text))->colorForeground(Eurekon\Style\Color::GREEN));
    }
}
