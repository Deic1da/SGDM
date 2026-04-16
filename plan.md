## Plan: Fechar Gaps do SGDM

Concluir os requisitos funcionais pendentes e integrar segurança, validação e rastreabilidade. A abordagem recomendada é fechar primeiro os bloqueadores de segurança e qualidade de dados (RBAC, validações e fluxo mínimo de doação), depois completar os fluxos de negócio (validação, entrega, descarte, busca e mapa), e por fim consolidar auditoria e testes.

**Steps**
1. Fase 1 — Bloqueadores de Segurança e Dados
2. Implementar controle de acesso por papel e vínculo ativo nas rotas sensíveis (farmacêutico, dono de entidade, admin, receptor), com middleware dedicado e agrupamento de rotas por permissão.
3. Implementar validações formais para CPF, CNPJ e CRF via Form Requests e aplicar nos endpoints de cadastro/edição. *parallel with step 2*
4. Fortalecer regra de senha no cadastro de usuário (complexidade mínima e restrições explícitas do requisito). *depends on 3*
5. Ajustar cadastro de medicamento para validar regras de embalagem e bloquear validade menor que 30 dias; direcionar rejeição para fluxo de descarte. *depends on 2*
6. Fase 2 — Fluxos de Negócio Principais
7. Implementar RF002 (cadastro de entidade) com fluxo de status pendente/aprovado e validação de responsável técnico ativo.
8. Implementar RF003 e RF004 (cadastro de farmacêutico e vínculo com entidade), incluindo convite/aceite e status de vínculo. *depends on 7*
9. Implementar RF005 (validação de doações) para aprovar/rejeitar medicamentos e refletir no status da doação e estoque da entidade. *depends on 8*
10. Implementar RF006 (entrega de medicamentos) com baixa de estoque e registro da entrega. *depends on 9*
11. Implementar RF011 (descarte) integrado aos rejeitados e vencidos. *depends on 9*
12. Fase 3 — Descoberta e Localização
13. Implementar RF010 (consulta por medicamento para receptor) usando estoque aprovado por entidade.
14. Implementar RF009 (mapa de pontos de coleta) com entidades aprovadas e georreferenciadas. *parallel with step 13*
15. Fase 4 — Rastreabilidade e Qualidade
16. Integrar RF007 (auditoria) em todos os eventos críticos: cadastro, vínculo, validação, entrega, descarte e alterações cadastrais.
17. Completar cobertura de testes de feature para autenticação, autorização, validação de doação, entrega e descarte; incluir cenários de negação de acesso e regressão.

**Relevant files**
- c:/Users/Melqui/Desktop/SGDM/documentos/Requisito_SGDM.txt — referência funcional e não funcional de escopo
- c:/Users/Melqui/Desktop/SGDM/routes/web.php — organização de rotas, grupos por middleware e proteção RBAC
- c:/Users/Melqui/Desktop/SGDM/app/Http/Controllers/AuthController.php — cadastro/login e regras de senha
- c:/Users/Melqui/Desktop/SGDM/app/Http/Controllers/MedicamentoDoacaoController.php — regras de aceite/rejeição no cadastro inicial
- c:/Users/Melqui/Desktop/SGDM/app/Models/User.php — papéis, relacionamentos e suporte a autorização
- c:/Users/Melqui/Desktop/SGDM/app/Models/MedicamentoDoacao.php — status e relacionamentos da doação
- c:/Users/Melqui/Desktop/SGDM/database/migrations/2026_04_15_120100_create_farmaceuticos_table.php — base de farmacêuticos
- c:/Users/Melqui/Desktop/SGDM/database/migrations/2026_04_15_120200_create_entidades_table.php — base de entidades
- c:/Users/Melqui/Desktop/SGDM/database/migrations/2026_04_15_120300_create_vinculos_farmaceutico_table.php — base de vínculo farmacêutico-entidade
- c:/Users/Melqui/Desktop/SGDM/database/migrations/2026_04_15_120400_create_log_auditoria_table.php — base de trilha de auditoria
- c:/Users/Melqui/Desktop/SGDM/database/migrations/2026_04_15_120600_create_validacoes_table.php — base de validações de doação
- c:/Users/Melqui/Desktop/SGDM/database/migrations/2026_04_15_120700_create_estoque_entidade_table.php — base de estoque por entidade
- c:/Users/Melqui/Desktop/SGDM/database/migrations/2026_04_15_120800_create_entregas_table.php — base de entregas
- c:/Users/Melqui/Desktop/SGDM/database/migrations/2026_04_15_120900_create_descarte_table.php — base de descarte
- c:/Users/Melqui/Desktop/SGDM/resources/views/login.blade.php — padrão visual já adotado
- c:/Users/Melqui/Desktop/SGDM/resources/views/CadastroFarmaceutico.blade.php — tela existente a conectar ao backend
- c:/Users/Melqui/Desktop/SGDM/resources/views/CadastroMedicamento.blade.php — tela existente a ajustar regras
- c:/Users/Melqui/Desktop/SGDM/resources/views/ValidarDoacao.blade.php — tela existente para RF005
- c:/Users/Melqui/Desktop/SGDM/resources/views/EstoqueMedicamento.blade.php — tela existente para RF006/RF010
- c:/Users/Melqui/Desktop/SGDM/resources/views/Descarte.blade.php — tela existente para RF011

**Verification**
1. Executar testes de feature de autenticação e autorização para garantir bloqueio por papel e vínculo.
2. Validar cenários de cadastro com CPF/CNPJ/CRF inválidos e senha fora da política, confirmando mensagens corretas.
3. Simular fluxo completo da doação: cadastro → validação farmacêutica → entrada em estoque → entrega ou descarte, verificando transições de status.
4. Confirmar gravação de log de auditoria em cada ação crítica (criar, aprovar, rejeitar, entregar, descartar).
5. Validar manualmente telas existentes conectadas ao backend mantendo padrão visual já usado em login/cadastro.

**Decisions**
- Incluído: fechamento dos RF001-RF011 com prioridade para segurança e rastreabilidade.
- Excluído neste ciclo: melhorias cosméticas grandes de UI fora do padrão atual e funcionalidades não previstas no requisito.
- Premissa: estrutura de banco atual será reaproveitada, com ajustes mínimos de schema apenas se necessário para consistência dos fluxos.

**Further Considerations**
1. Definir cedo se RBAC ficará em tabela dedicada de papéis/permissões ou em enum/campos do usuário para reduzir retrabalho.
2. Definir estratégia de mapa (provedor e custos) antes de implementar RF009 para evitar refação da camada de frontend/API.
3. Definir formato padrão do log de auditoria (payload e identificação do ator) antes de integrar eventos em massa.