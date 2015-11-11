<?php

namespace Todstoychev\Icr\Console;

use Illuminate\Console\Command;
use Todstoychev\Icr\Processor;

class RebuildCommand extends Command
{

    /**
     * @var string
     */
    protected $name = 'icr:rebuild';

    /**
     * @var string
     */
    protected $description = 'Rebuild all images in the provided context.';

    /**
     * @var Processor
     */
    protected $processor;

    /**
     * Create a new command instance.
     *
     * @param Processor $processor
     */
    public function __construct(Processor $processor)
    {
        parent::__construct();

        $this->setProcessor($processor);
    }

    /**
     * @return Processor
     */
    public function getProcessor()
    {
        return $this->processor;
    }

    /**
     * @param Processor $processor
     *
     * @return RebuildCommand
     */
    public function setProcessor(Processor $processor)
    {
        $this->processor = $processor;

        return $this;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $context = $this->ask('Provide context to rebuild', 'default');

        try {
            $this->getProcessor()->rebuild($context);
        } catch (\Exception $e) {
            $this->error("\n\n\t" . $e->getMessage() . "\n");
        }
    }
}
