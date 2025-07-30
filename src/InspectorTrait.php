<?php

declare(strict_types=1);

namespace Notdefine\Workflow;

use Inspector\Configuration;
use Inspector\Inspector;
use NeuronAI\Agent;
use NeuronAI\Observability\AgentMonitoring;

trait InspectorTrait
{
    private ?Inspector $inspector = null;

    public function addAgentMonitoring(Agent $agent): Agent
    {
        if ($this->inspector === null) {
            $inspectorKey = getenv('INSPECTOR_API_KEY');
            if (empty($inspectorKey)) {
                return $agent;
            }

            $configuration = new Configuration($inspectorKey);
            $this->inspector = new Inspector($configuration);
        }

        $agent->observe(
            new AgentMonitoring($this->inspector),
        );
        return $agent;
    }
}

