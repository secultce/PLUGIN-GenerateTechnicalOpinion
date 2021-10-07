<?php
use MapasCulturais\App;
use MapasCulturais\i;

$route = App::i()->createUrl('reportEvaluations', 'reportEvaluations', ['opportunityId' => $opportunity->id]);

?>

<a class="btn btn-default" ng-click="editbox.open('report-evaluation-technical-options', $event)"
    rel="noopener noreferrer">Gerar parecer técnico</a>

<edit-box id="report-evaluation-technical-options" position="top"
    title="<?php i::esc_attr_e('Gerar parecer técnico')?>"
    cancel-label="Cancelar" close-on-cancel="true">

    <form class="form-report-evaluation-technical-options"
        action="<?=$route?>" method="GET">
        <label for="inscription">Inscrição:</label>
        <input type="text" name="inscription" id="inscription">
        <button class="btn btn-primary" type="submit">Gerar</button>
    </form>
</edit-box>
