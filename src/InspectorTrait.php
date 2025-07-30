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

            $this->inspector = new Inspector(new Configuration($inspectorKey));
        }

        return $agent->observe(
            new AgentMonitoring($this->inspector),
        );
    }
}

