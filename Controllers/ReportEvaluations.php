<?php

namespace GenerateTechnicalOpinion\Controllers;

use MapasCulturais\App;
use MapasCulturais\Controller;
use MapasCulturais\i;
use Dompdf\Dompdf;
use DateTime;

class ReportEvaluations extends Controller
{
    public function ALL_reportEvaluations() 
    {
        $this->requireAuthentication();
        $app = App::i();

        $opportunityId = (int) $this->data['opportunityId'];
        $opportunity = $app->repo("Opportunity")->find($opportunityId);
        $nameOpportunity = $opportunity->parent->name;

        $inscription = $this->data['inscription'];
        if(!$inscription){
            $this->errorJson("Nao foi informado nenhuma inscricao!");
        }

        $registration = $app->repo("Registration")->find($inscription);
        if(!$registration){
            $this->errorJson("Inscricao $inscription nao foi encontrada!");
        }

        $today = new DateTime('now');
        $todayFormated = $today->format('d-m-Y H:i');

        $asp = '"';
        $n = '\n';
        $r = '\r';

        $sqlRegistrationData = "
            with parecer_tecnico_2852 as (
                with avaliadores_rank as(
                    with avaliadores as (
                        select distinct
                            re.user_id as id_av,
                            r.number as insc,
                            ag.name as nome_avaliador,
                            (rank()over(partition by re.user_id order by re.user_id asc)) as rank_avaliador,
                            replace((replace((replace((replace(((to_json(re.evaluation_data::jsonb->'obs'))::TEXT),'$asp','')), '$n', '')), '$r', '')), '\', '') as parecer_tecnico,
                            re.result as nota,
                            CASE
                                WHEN (REPLACE(REPLACE(REPLACE(REPLACE(to_json(re.evaluation_data::jsonb->'c-1626198737475')::TEXT,'$asp',''),'$asp', '' ), '$asp', ''), '$asp', '')) = ''  THEN 0
                                WHEN (REPLACE(REPLACE(REPLACE(REPLACE(to_json(re.evaluation_data::jsonb->'c-1626198737475')::TEXT,'$asp',''),'$asp', '' ), '$asp', ''), '$asp', '')) is null  THEN 0
                                ELSE (REPLACE(REPLACE(REPLACE(REPLACE(to_json(re.evaluation_data::jsonb->'c-1626198737475')::TEXT,'$asp',''),'$asp', '' ), '$asp', ''), '$asp', ''))::NUMERIC
                            END as merit_cultural_P4_A, 
                            CASE
                                WHEN (REPLACE(REPLACE(REPLACE(REPLACE(to_json(re.evaluation_data::jsonb->'c-1626198768803')::TEXT,'$asp',''),'$asp', '' ), '$asp', ''), '$asp', '')) = ''  THEN 0
                                WHEN (REPLACE(REPLACE(REPLACE(REPLACE(to_json(re.evaluation_data::jsonb->'c-1626198768803')::TEXT,'$asp',''),'$asp', '' ), '$asp', ''), '$asp', '')) is null  THEN 0
                                ELSE (REPLACE(REPLACE(REPLACE(REPLACE(to_json(re.evaluation_data::jsonb->'c-1626198768803')::TEXT,'$asp',''),'$asp', '' ), '$asp', ''), '$asp', ''))::NUMERIC
                            END as merit_cultural_P4_B, 
                            CASE
                                WHEN (REPLACE(REPLACE(REPLACE(REPLACE(to_json(re.evaluation_data::jsonb->'c-1626198792170')::TEXT,'$asp',''),'$asp', '' ), '$asp', ''), '$asp', '')) = ''  THEN 0
                                WHEN (REPLACE(REPLACE(REPLACE(REPLACE(to_json(re.evaluation_data::jsonb->'c-1626198792170')::TEXT,'$asp',''),'$asp', '' ), '$asp', ''), '$asp', '')) is null  THEN 0
                                ELSE (REPLACE(REPLACE(REPLACE(REPLACE(to_json(re.evaluation_data::jsonb->'c-1626198792170')::TEXT,'$asp',''),'$asp', '' ), '$asp', ''), '$asp', ''))::NUMERIC
                            END as merit_cultural_P4_C, 
                            CASE
                                WHEN (REPLACE(REPLACE(REPLACE(REPLACE(to_json(re.evaluation_data::jsonb->'c-1626198813132')::TEXT,'$asp',''),'$asp', '' ), '$asp', ''), '$asp', '')) = ''  THEN 0
                                WHEN (REPLACE(REPLACE(REPLACE(REPLACE(to_json(re.evaluation_data::jsonb->'c-1626198813132')::TEXT,'$asp',''),'$asp', '' ), '$asp', ''), '$asp', '')) is null  THEN 0
                                ELSE (REPLACE(REPLACE(REPLACE(REPLACE(to_json(re.evaluation_data::jsonb->'c-1626198813132')::TEXT,'$asp',''),'$asp', '' ), '$asp', ''), '$asp', ''))::NUMERIC
                            END as merit_cultural_P2_D, 
                            CASE
                                WHEN (REPLACE(REPLACE(REPLACE(REPLACE(to_json(re.evaluation_data::jsonb->'c-1626198834612')::TEXT,'$asp',''),'$asp', '' ), '$asp', ''), '$asp', '')) = ''  THEN 0
                                WHEN (REPLACE(REPLACE(REPLACE(REPLACE(to_json(re.evaluation_data::jsonb->'c-1626198834612')::TEXT,'$asp',''),'$asp', '' ), '$asp', ''), '$asp', '')) is null  THEN 0
                                ELSE (REPLACE(REPLACE(REPLACE(REPLACE(to_json(re.evaluation_data::jsonb->'c-1626198834612')::TEXT,'$asp',''),'$asp', '' ), '$asp', ''), '$asp', ''))::NUMERIC
                            END as merit_cultural_P1_E, 
                            CASE
                                WHEN (REPLACE(REPLACE(REPLACE(REPLACE(to_json(re.evaluation_data::jsonb->'c-1626198887026')::TEXT,'$asp',''),'$asp', '' ), '$asp', ''), '$asp', '')) = ''  THEN 0
                                WHEN (REPLACE(REPLACE(REPLACE(REPLACE(to_json(re.evaluation_data::jsonb->'c-1626198887026')::TEXT,'$asp',''),'$asp', '' ), '$asp', ''), '$asp', '')) is null  THEN 0
                                ELSE (REPLACE(REPLACE(REPLACE(REPLACE(to_json(re.evaluation_data::jsonb->'c-1626198887026')::TEXT,'$asp',''),'$asp', '' ), '$asp', ''), '$asp', ''))::NUMERIC
                            END as tesouro_vivo_nota, 
                            CASE
                                WHEN (REPLACE(REPLACE(REPLACE(REPLACE(to_json(re.evaluation_data::jsonb->'c-1626199026841')::TEXT,'$asp',''),'$asp', '' ), '$asp', ''), '$asp', '')) = ''  THEN 0
                                WHEN (REPLACE(REPLACE(REPLACE(REPLACE(to_json(re.evaluation_data::jsonb->'c-1626199026841')::TEXT,'$asp',''),'$asp', '' ), '$asp', ''), '$asp', '')) is null  THEN 0
                                ELSE (REPLACE(REPLACE(REPLACE(REPLACE(to_json(re.evaluation_data::jsonb->'c-1626199026841')::TEXT,'$asp',''),'$asp', '' ), '$asp', ''), '$asp', ''))::NUMERIC
                            END as crite_def1_nota, 
                            CASE
                                WHEN (REPLACE(REPLACE(REPLACE(REPLACE(to_json(re.evaluation_data::jsonb->'c-1626199031572')::TEXT,'$asp',''),'$asp', '' ), '$asp', ''), '$asp', '')) = ''  THEN 0
                                WHEN (REPLACE(REPLACE(REPLACE(REPLACE(to_json(re.evaluation_data::jsonb->'c-1626199031572')::TEXT,'$asp',''),'$asp', '' ), '$asp', ''), '$asp', '')) is null  THEN 0
                                ELSE (REPLACE(REPLACE(REPLACE(REPLACE(to_json(re.evaluation_data::jsonb->'c-1626199031572')::TEXT,'$asp',''),'$asp', '' ), '$asp', ''), '$asp', ''))::NUMERIC
                            END as crite_def2_nota 
                        from
                            public.registration as r
                            left join public.registration_evaluation as re
                            on re.registration_id  = r.id
                            inner join public.agent as ag
                            on ag.user_id = re.user_id
                        where
                            r.opportunity_id = $opportunityId
                            and r.number = 'on-$inscription'
                    )
                    select
                        *,
                        row_number()over(order by id_av)
                    from
                        avaliadores
                    )
                    select distinct
                        ----------- PROPONENTE ------------
                        RANK()OVER(PARTITION BY r.number ORDER BY rm_proponente.id DESC) as id_name_proponente,
                        r.number as num_inscricao,
                        upper(rm_proponente.value) as nome_proponente,
                        case
                        when upper(r.agents_data::json->'owner'->>'nomeCompleto') is not null then upper(r.agents_data::json->'owner'->>'nomeCompleto')
                        else
                        upper(r.agents_data::json->'owner'->>'name')
                        end as nome_responsavel,
                        upper(rm_project.value) as nome_projeto,
                        upper(r.category) as categoria_modalidade,
                        upper(rm_cidade.value) as nome_cidade,
                        upper(rm_macro_regiao.value) as nome_macro_regiao,
                        ----------- AVALIADOR 1 ------------
                        av_1.id_av as id_av_1,
                        upper(av_1.nome_avaliador) as nome_avaliador_1,
                        av_1.nota as nota_av_1,
                        round((av_1.merit_cultural_P4_A * 4) +
                        (av_1.merit_cultural_P4_B * 4) +
                        (av_1.merit_cultural_P4_C * 4) +
                        (av_1.merit_cultural_P2_D * 2) +
                        (av_1.merit_cultural_P1_E * 1), 1) as crite_merit_cult_nota_av1,
                        av_1.tesouro_vivo_nota as ponto_extra_tesouro_vivo_av1,
                        (av_1.crite_def1_nota + av_1.crite_def2_nota) as ponto_extra_crite_def_av1,
                        av_1.parecer_tecnico as parecer_tecnico_av1,
                        ----------- AVALIADOR 2 ------------
                        av_2.id_av as id_av_2,
                        upper(av_2.nome_avaliador) as nome_avaliador_2,
                        av_2.nota as nota_av_2,
                        round((av_2.merit_cultural_P4_A * 4) +
                        (av_2.merit_cultural_P4_B * 4) +
                        (av_2.merit_cultural_P4_C * 4) +
                        (av_2.merit_cultural_P2_D * 2) +
                        (av_2.merit_cultural_P1_E * 1), 1) as crite_merit_cult_nota_av2,
                        av_2.tesouro_vivo_nota as ponto_extra_tesouro_vivo_av2,
                        (av_2.crite_def1_nota + av_2.crite_def2_nota) as ponto_extra_crite_def_av2,
                        av_2.parecer_tecnico as parecer_tecnico_av2,
                        ----------- AVALIADOR 3 ------------
                        av_3.id_av as id_av_3,
                        upper(av_3.nome_avaliador) as nome_avaliador_3,
                        av_3.nota as nota_av_3,
                        round((av_3.merit_cultural_P4_A * 4) +
                        (av_3.merit_cultural_P4_B * 4) +
                        (av_3.merit_cultural_P4_C * 4) +
                        (av_3.merit_cultural_P2_D * 2) +
                        (av_3.merit_cultural_P1_E * 1), 1) as crite_merit_cult_nota_av3,
                        av_3.tesouro_vivo_nota as ponto_extra_tesouro_vivo_av3,
                        (av_3.crite_def1_nota + av_3.crite_def2_nota) as ponto_extra_crite_def_av3,
                        av_3.parecer_tecnico as parecer_tecnico_av3,
                        ----------- NOTA FINAL ------------
                        ROUND(((COALESCE(CAST(av_1.nota AS numeric), 0) +
                        COALESCE(CAST(av_2.nota AS numeric), 0) +
                        COALESCE(CAST(av_3.nota AS numeric), 0))) / 3, 1) nota_final_da_avaliacao
                    from
                        public.registration as r
                            inner join public.registration_meta as rm_project
                                on rm_project.object_id = r.id
                                and rm_project.key = 'projectName'
                            inner join public.registration_meta as rm_proponente
                                on rm_proponente.object_id = r.id
                                and rm_proponente.key = 'field_26641'
                            inner join public.registration_meta as rm_cidade
                                on rm_cidade.object_id = r.id
                                and rm_cidade.key = 'field_26635'
                            inner join public.registration_meta as rm_macro_regiao
                                on rm_macro_regiao.object_id = r.id
                                and rm_macro_regiao.key = 'field_26634',
                        avaliadores_rank as av_1, 
                        avaliadores_rank as av_2, 
                        avaliadores_rank as av_3
                    where
                        r.opportunity_id = 3154
                        and status = 10
                        and av_1.row_number = 1
                        and replace(r.number, 'on-', '')::INT = replace(av_1.insc, 'on-', '')::INT
                        and av_2.row_number = 2
                        and replace(r.number, 'on-', '')::INT = replace(av_2.insc, 'on-', '')::INT
                        and av_3.row_number = 3
                        and replace(r.number, 'on-', '')::INT = replace(av_3.insc, 'on-', '')::INT
            )
            select 
                *
            from
                parecer_tecnico_2852 as pt
            where
                pt.id_name_proponente = 1
        ";

        // dd($sqlRegistrationData);

        $stmt = $app->em->getConnection()->prepare($sqlRegistrationData);
        $stmt->execute();
        $data = (object) $stmt->fetch();  

        // dd($data);

        // Criando arquivo PDF...
        $dompdf = new Dompdf();

        $dompdf->setPaper('A4', 'portrait');

        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <title>Parecer Tecnico</title>
            <style>
                #header { position: fixed; top: 2px; text-align: center; }
                #footer { position: fixed; bottom: 20px; text-align: center; }
            </style>
        </head> 
        <body>

                <div style='top: 2px; text-align: center;'>
                    <img width='500' height='70' src='data:image/png;base64,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'>
                </div>

                <br>

                <h5 style='text-align: center;'> PARECER TECNICO - $nameOpportunity </h5>

                <div style='line-height: 0.3; font-size: 12px;'>
                    <p><strong>INSCRIÇÃO:</strong> $data->num_inscricao</p>
                    <p><strong>PROPONENTE:</strong> $data->nome_proponente </p>
                    <p><strong>RESPONSÁVEL:</strong> $data->nome_responsavel </p>
                    <p><strong>PROJETO:</strong> $data->nome_projeto </p>
                    <p><strong>CATEGORIA:</strong> $data->categoria_modalidade </p>
                    <p><strong>CIDADE:</strong> $data->nome_cidade </p>
                    <p><strong>MACRO-REGIÃO:</strong> $data->nome_macro_regiao </p>

                    <br>
                    <br>
                    <br>
                    <p><strong>PONTUAÇÃO FINAL (PONTUAÇÃO MÁXIMA 63): $data->nota_final_da_avaliacao</strong></p>
                    <br>

                    <p><strong>AVALIADOR A*</strong></p>
                    <p><strong>NOTA:</strong> $data->nota_av_1</p>
                    <p><strong>CRITÉRIOS DE MÉRITO CULTURAL:</strong> $data->crite_merit_cult_nota_av1</p>
                    <p><strong>PONTUAÇÃO EXTRA: TESOURO VIVO DA CULTURA:</strong> $data->ponto_extra_tesouro_vivo_av1</p> 
                    <p><strong>PONTUAÇÃO EXTRA: POLÍTICA DE ACESSIBILIDADE:</strong> $data->ponto_extra_crite_def_av1</p>
                    <p style='line-height: 1.3; font-size: 13px; text-align: justify;' ><strong>PARECER:</strong> $data->parecer_tecnico_av1</p>

                    <br>
                    <br>
                    <br>
                    <br>

                    <p><strong>AVALIADOR B*</strong></p>
                    <p><strong>NOTA:</strong> $data->nota_av_2</p>
                    <p><strong>CRITÉRIOS DE MÉRITO CULTURAL:</strong> $data->crite_merit_cult_nota_av2</p>
                    <p><strong>PONTUAÇÃO EXTRA: TESOURO VIVO DA CULTURA:</strong> $data->ponto_extra_tesouro_vivo_av2</p>
                    <p><strong>PONTUAÇÃO EXTRA: POLÍTICA DE ACESSIBILIDADE:</strong> $data->ponto_extra_crite_def_av2</p>
                    <p style='line-height: 1.3; font-size: 13px; text-align: justify;' ><strong>PARECER:</strong> $data->parecer_tecnico_av2</p>

                    <br>
                    <br>
                    <br>
                    <br>

                    <p><strong>AVALIADOR C*</strong></p>
                    <p><strong>NOTA:</strong> $data->nota_av_3</p>
                    <p><strong>CRITÉRIOS DE MÉRITO CULTURAL:</strong> $data->crite_merit_cult_nota_av3</p>
                    <p><strong>PONTUAÇÃO EXTRA: TESOURO VIVO DA CULTURA:</strong> $data->ponto_extra_tesouro_vivo_av3</p>
                    <p><strong>PONTUAÇÃO EXTRA: POLÍTICA DE ACESSIBILIDADE:</strong> $data->ponto_extra_crite_def_av3</p>
                    <p style='line-height: 1.3; font-size: 13px; text-align: justify;' ><strong>PARECER:</strong> $data->parecer_tecnico_av3</p>

                    <br>
                    <br>
                    <br>

                    <p>Fortaleza, $todayFormated.</p>

                    <br>
                    <br>

                    <p style='font-size: 14px;'><strong>COMISSÃO DE SELEÇÃO*:</strong></p>
                    <p><strong> - $data->nome_avaliador_2</strong></p>
                    <p><strong> - $data->nome_avaliador_1</strong></p>
                    <p><strong> - $data->nome_avaliador_3</strong></p>

                    <br>

                    <p style='line-height: 1.0; font-size: 16px;'>*Para manter a impessoalidade do processo de avaliação, os avaliadores A,B e C não estão na mesma ordem dos nomes apresentados na lista da comissão de seleção.</p>

                </div>
            </div>

            <div id='footer' style='line-height: 0.3;'>
                <p style='font-size: 13px;'><strong>SECRETARIA DA CULTURA DO ESTADO DO CEARÁ – SECULT</strong></p>
                <p style='font-size: 11px;'>Rua Major Facundo, Nº 500 – Centro – CEP: 60.025-100 – Fortaleza – Ceará / Telefone: (85) 3101-6767</p>
            </div>

        </body>
        </html>
        "; 

        $dompdf->loadHtml($html);

        $dompdf->render();

        $dompdf->stream();

    }
}

?>