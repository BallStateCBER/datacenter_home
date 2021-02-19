<?php
declare(strict_types=1);

namespace App\Command;

use App\Application;
use Cake\Cache\Cache;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;

/**
 * RefreshLatestRelease command.
 */
class RefreshLatestReleaseCommand extends Command
{
    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        return $parser;
    }

    /**
     * If a "latest release" value can be retrieved, updates the cache. Otherwise, does nothing.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|void|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $url = Configure::read('latestReleaseUrl');
        $io->out('Fetching latest release from ' . $url . '...');
        if (Configure::read('debug')) {
            $streamOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ];
            $results = file_get_contents($url, false, stream_context_create($streamOptions));
        } else {
            $results = file_get_contents($url);
        }

        $results = is_string($results) ? json_decode($results) : false;

        if ($results && isset($results->release)) {
            $io->out('Updating cache...');
            Cache::write(Application::LATEST_RELEASE_CACHE_KEY, $results->release, 'long');
            $io->out('Done');

            return null;
        }

        $io->out('No release data was returned');

        return null;
    }
}
