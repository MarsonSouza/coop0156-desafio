<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes — Coop0156</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Outfit', 'sans-serif'] },
                    colors: {
                        darkBg: '#0b0f19',
                        panelBg: '#131c2e',
                        panelBorder: '#1e2d4a',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #0b0f19;
            background-image:
                radial-gradient(at 0% 0%, hsla(142, 70%, 15%, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, hsla(220, 70%, 15%, 0.15) 0px, transparent 50%);
        }
        .glass-panel {
            background: rgba(19, 28, 46, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(30, 45, 74, 0.6);
        }
    </style>
</head>
<body class="text-slate-200 min-h-screen flex flex-col font-sans">

    <header class="border-b border-panelBorder/50 py-5 glass-panel sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-green-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-green-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-tight bg-gradient-to-r from-emerald-400 to-green-300 bg-clip-text text-transparent">Coop0156</h1>
                    <p class="text-xs text-slate-400">Cadastro de Clientes</p>
                </div>
            </div>
            <nav class="flex items-center gap-1 text-sm">
                <a href="/" class="px-3 py-1.5 rounded-lg text-slate-400 hover:text-slate-100 hover:bg-white/5 transition-all">Análise</a>
                <a href="/clientes" class="px-3 py-1.5 rounded-lg text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 font-medium">Clientes</a>
            </nav>
        </div>
    </header>

    <main class="flex-grow max-w-6xl mx-auto px-4 py-12 w-full space-y-8">

        <!-- Formulário -->
        <section class="glass-panel rounded-3xl p-8 shadow-2xl">
            <h2 id="form-titulo" class="text-2xl font-semibold mb-6">Novo Cliente</h2>

            <form id="form-cliente" class="space-y-6">
                <input type="hidden" id="c-id">

                <div id="form-erro" class="hidden bg-red-500/10 border border-red-500/20 rounded-xl p-4 text-sm text-red-300"></div>
                <div id="form-ok" class="hidden bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-4 text-sm text-emerald-300"></div>

                <div>
                    <label for="c-nome" class="block text-sm font-medium text-slate-400 mb-2">Nome Completo</label>
                    <input type="text" id="c-nome" required placeholder="Nome do cliente"
                        class="w-full bg-slate-950/50 border border-panelBorder rounded-xl px-4 py-3 text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="c-cpf" class="block text-sm font-medium text-slate-400 mb-2">CPF</label>
                        <input type="text" id="c-cpf" required placeholder="000.000.000-00" inputmode="numeric" autocomplete="off" maxlength="14"
                            class="w-full bg-slate-950/50 border border-panelBorder rounded-xl px-4 py-3 text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label for="c-renda" class="block text-sm font-medium text-slate-400 mb-2">Renda Mensal (R$)</label>
                        <input type="text" id="c-renda" required placeholder="R$ 0,00" inputmode="numeric" autocomplete="off"
                            class="w-full bg-slate-950/50 border border-panelBorder rounded-xl px-4 py-3 text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="c-email" class="block text-sm font-medium text-slate-400 mb-2">E-mail</label>
                        <input type="email" id="c-email" required placeholder="cliente@exemplo.com" autocomplete="off"
                            class="w-full bg-slate-950/50 border border-panelBorder rounded-xl px-4 py-3 text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label for="c-telefone" class="block text-sm font-medium text-slate-400 mb-2">Telefone <span class="text-slate-600">(opcional)</span></label>
                        <input type="text" id="c-telefone" placeholder="(00) 00000-0000" inputmode="numeric" autocomplete="off" maxlength="16"
                            class="w-full bg-slate-950/50 border border-panelBorder rounded-xl px-4 py-3 text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" id="c-submit"
                        class="flex-1 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-semibold py-3.5 px-6 rounded-xl transition-all duration-200 active:scale-98 shadow-lg shadow-emerald-500/10">
                        Cadastrar Cliente
                    </button>
                    <button type="button" id="c-cancelar"
                        class="hidden px-6 py-3.5 rounded-xl border border-panelBorder text-slate-400 hover:text-slate-200 hover:border-slate-500 transition-all font-medium text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </section>

        <!-- Listagem -->
        <section class="glass-panel rounded-3xl p-8 shadow-2xl">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-semibold">Clientes Cadastrados</h2>
                <button id="btn-atualizar" class="text-sm text-slate-400 hover:text-emerald-400 transition-all">Atualizar</button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500 border-b border-panelBorder">
                            <th class="pb-3 pr-4 font-medium">Nome</th>
                            <th class="pb-3 pr-4 font-medium">CPF</th>
                            <th class="pb-3 pr-4 font-medium">E-mail</th>
                            <th class="pb-3 pr-4 font-medium">Telefone</th>
                            <th class="pb-3 pr-4 font-medium text-right">Renda</th>
                            <th class="pb-3 pr-4 font-medium text-center">Análises</th>
                            <th class="pb-3 font-medium text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-clientes" class="divide-y divide-panelBorder/60"></tbody>
                </table>
            </div>

            <div id="lista-vazia" class="hidden text-center text-slate-500 py-12 text-sm">Nenhum cliente cadastrado ainda.</div>

            <div id="paginacao" class="hidden items-center justify-between mt-6 text-sm text-slate-400">
                <button id="pg-prev" class="px-3 py-1.5 rounded-lg border border-panelBorder hover:border-slate-500 disabled:opacity-40 disabled:cursor-not-allowed transition-all">Anterior</button>
                <span id="pg-info"></span>
                <button id="pg-next" class="px-3 py-1.5 rounded-lg border border-panelBorder hover:border-slate-500 disabled:opacity-40 disabled:cursor-not-allowed transition-all">Próxima</button>
            </div>
        </section>

    </main>

    <footer class="border-t border-panelBorder/40 py-6 text-center text-xs text-slate-600">
        <p>&copy; 2026 Coop0156. Desafio Técnico Laravel.</p>
    </footer>

    <!-- Modal: análises do cliente -->
    <div id="modal-analises" class="hidden fixed inset-0 z-[60] bg-black/70 backdrop-blur-sm flex items-start justify-center p-4 overflow-y-auto">
        <div class="glass-panel rounded-3xl w-full max-w-2xl my-10 p-8 shadow-2xl relative">
            <button id="modal-fechar" class="absolute top-5 right-5 text-slate-500 hover:text-slate-200 transition-all" aria-label="Fechar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="mb-6">
                <h3 id="modal-nome" class="text-xl font-semibold text-slate-100">—</h3>
                <p id="modal-sub" class="text-sm text-slate-400 mt-1">—</p>
            </div>
            <div id="modal-corpo" class="space-y-3"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const $ = (id) => document.getElementById(id);
            const form = $('form-cliente');
            const POR_PAGINA = 10;
            let paginaAtual = 1;

            const soDigitos = (v) => (v || '').replace(/\D/g, '');
            const brl = (v) => Number(v).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
            const escapeHtml = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => (
                { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]
            ));

            const mascaraCpf = (v) => {
                const d = soDigitos(v).slice(0, 11);
                let out = d.slice(0, 3);
                if (d.length > 3) out += '.' + d.slice(3, 6);
                if (d.length > 6) out += '.' + d.slice(6, 9);
                if (d.length > 9) out += '-' + d.slice(9, 11);
                return out;
            };
            const formataCpf = (v) => mascaraCpf(soDigitos(v).padStart(11, '0'));

            const mascaraTelefone = (v) => {
                const d = soDigitos(v).slice(0, 11);
                if (d.length <= 10) {
                    let out = d.slice(0, 2) ? `(${d.slice(0, 2)}` : '';
                    if (d.length > 2) out += `) ${d.slice(2, 6)}`;
                    if (d.length > 6) out += `-${d.slice(6, 10)}`;
                    return out;
                }
                return `(${d.slice(0, 2)}) ${d.slice(2, 7)}-${d.slice(7, 11)}`;
            };

            const mascaraMoeda = (v) => {
                const d = soDigitos(v).slice(0, 15);
                return d === '' ? '' : (Number(d) / 100).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
            };
            const moedaParaNumero = (v) => {
                const d = soDigitos(v);
                return d === '' ? '' : Number(d) / 100;
            };

            function ligarMascara(el, fn) {
                el.addEventListener('input', () => {
                    const pos = el.value.length - el.selectionStart;
                    el.value = fn(el.value);
                    const novo = Math.max(0, el.value.length - pos);
                    el.setSelectionRange(novo, novo);
                });
            }
            ligarMascara($('c-cpf'), mascaraCpf);
            ligarMascara($('c-telefone'), mascaraTelefone);
            ligarMascara($('c-renda'), mascaraMoeda);

            function esconderMsgs() {
                $('form-erro').classList.add('hidden');
                $('form-ok').classList.add('hidden');
            }
            function mostrarErros(msgs) {
                esconderMsgs();
                const box = $('form-erro');
                box.innerHTML = '<ul class="list-disc list-inside space-y-1">' +
                    msgs.map((m) => `<li>${escapeHtml(m)}</li>`).join('') + '</ul>';
                box.classList.remove('hidden');
            }
            function mostrarOk(msg) {
                esconderMsgs();
                $('form-ok').textContent = msg;
                $('form-ok').classList.remove('hidden');
            }

            function entrarModoEdicao(c) {
                $('c-id').value = c.id;
                $('c-nome').value = c.nome;
                $('c-cpf').value = formataCpf(c.cpf);
                $('c-email').value = c.email ?? '';
                $('c-telefone').value = c.telefone ? mascaraTelefone(c.telefone) : '';
                $('c-renda').value = mascaraMoeda(String(Math.round(Number(c.renda_mensal) * 100)));
                $('form-titulo').textContent = 'Editar Cliente #' + c.id;
                $('c-submit').textContent = 'Salvar Alterações';
                $('c-cancelar').classList.remove('hidden');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
            function sairModoEdicao() {
                form.reset();
                $('c-id').value = '';
                $('form-titulo').textContent = 'Novo Cliente';
                $('c-submit').textContent = 'Cadastrar Cliente';
                $('c-cancelar').classList.add('hidden');
                esconderMsgs();
            }
            $('c-cancelar').addEventListener('click', sairModoEdicao);

            async function carregar(pagina = 1) {
                const resp = await fetch(`/api/clientes?page=${pagina}&limit=${POR_PAGINA}`, { headers: { Accept: 'application/json' } });
                const body = await resp.json();
                paginaAtual = body.meta.current_page;

                const tbody = $('tbody-clientes');
                tbody.innerHTML = '';

                if (body.data.length === 0) {
                    $('lista-vazia').classList.remove('hidden');
                    $('paginacao').classList.add('hidden');
                    return;
                }
                $('lista-vazia').classList.add('hidden');

                for (const c of body.data) {
                    const tr = document.createElement('tr');
                    tr.className = 'text-slate-200';
                    const temAnalises = (c.analises_count ?? 0) > 0;
                    tr.innerHTML = `
                        <td class="py-3 pr-4 font-medium">
                            <button data-acao="analises" class="text-left hover:text-emerald-400 transition-all">${escapeHtml(c.nome)}</button>
                        </td>
                        <td class="py-3 pr-4 text-slate-400 whitespace-nowrap">${formataCpf(c.cpf)}</td>
                        <td class="py-3 pr-4 text-slate-400">${escapeHtml(c.email ?? '—')}</td>
                        <td class="py-3 pr-4 text-slate-400 whitespace-nowrap">${c.telefone ? mascaraTelefone(c.telefone) : '—'}</td>
                        <td class="py-3 pr-4 text-right whitespace-nowrap">${brl(c.renda_mensal)}</td>
                        <td class="py-3 pr-4 text-center">
                            <button data-acao="analises" ${temAnalises ? '' : 'disabled'}
                                class="inline-flex items-center justify-center min-w-[1.5rem] px-1.5 py-0.5 rounded-md text-xs bg-white/5 text-slate-300 ${temAnalises ? 'hover:bg-emerald-500/15 hover:text-emerald-300 cursor-pointer' : 'opacity-50 cursor-default'} transition-all">${c.analises_count ?? 0}</button>
                        </td>
                        <td class="py-3 text-right whitespace-nowrap">
                            <button data-acao="editar" class="text-amber-400 hover:text-amber-300 px-2 transition-all">Editar</button>
                            <button data-acao="excluir" class="text-red-400 hover:text-red-300 px-2 transition-all">Excluir</button>
                        </td>`;
                    tr.querySelectorAll('[data-acao="analises"]').forEach((el) => el.addEventListener('click', () => abrirAnalises(c)));
                    tr.querySelector('[data-acao="editar"]').addEventListener('click', () => entrarModoEdicao(c));
                    tr.querySelector('[data-acao="excluir"]').addEventListener('click', () => excluir(c));
                    tbody.appendChild(tr);
                }

                const { current_page, last_page, total } = body.meta;
                $('pg-info').textContent = `Página ${current_page} de ${last_page} · ${total} cliente(s)`;
                $('pg-prev').disabled = current_page <= 1;
                $('pg-next').disabled = current_page >= last_page;
                $('paginacao').classList.toggle('hidden', last_page <= 1);
                $('paginacao').classList.toggle('flex', last_page > 1);
            }

            async function excluir(c) {
                if (!confirm(`Excluir o cliente "${c.nome}"?`)) return;
                const resp = await fetch(`/api/clientes/${c.id}`, {
                    method: 'DELETE',
                    headers: { Accept: 'application/json' },
                });
                if (resp.status === 204) {
                    if ($('c-id').value === String(c.id)) sairModoEdicao();
                    mostrarOk(`Cliente "${c.nome}" removido.`);
                    carregar(paginaAtual);
                } else {
                    mostrarErros(['Não foi possível remover o cliente.']);
                }
            }

            $('pg-prev').addEventListener('click', () => carregar(paginaAtual - 1));
            $('pg-next').addEventListener('click', () => carregar(paginaAtual + 1));
            $('btn-atualizar').addEventListener('click', () => carregar(paginaAtual));

            const rotuloStatus = {
                pendente: ['Pendente', 'bg-slate-500/10 text-slate-300 border-slate-500/20'],
                aprovado: ['Aprovado', 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'],
                reprovado: ['Reprovado', 'bg-red-500/10 text-red-400 border-red-500/20'],
                processando_contratacao: ['Processando', 'bg-blue-500/10 text-blue-400 border-blue-500/20'],
                contratado: ['Contratado', 'bg-emerald-500/15 text-emerald-300 border-emerald-500/30'],
            };

            function fecharModal() {
                $('modal-analises').classList.add('hidden');
            }
            $('modal-fechar').addEventListener('click', fecharModal);
            $('modal-analises').addEventListener('click', (e) => {
                if (e.target === $('modal-analises')) fecharModal();
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') fecharModal();
            });

            function cardAnalise(a) {
                const [rotulo, cor] = rotuloStatus[a.status] ?? [a.status, 'bg-white/5 text-slate-300 border-white/10'];
                const data = a.criado_em ? new Date(a.criado_em).toLocaleString('pt-BR') : '';
                const linhas = [];
                linhas.push(`<span class="text-slate-400">Valor solicitado</span><span class="text-slate-100 text-right">${brl(a.valor_solicitado)}</span>`);
                if (a.score != null) linhas.push(`<span class="text-slate-400">Score</span><span class="text-slate-100 text-right">${a.score}</span>`);
                if (a.taxa_juros != null) linhas.push(`<span class="text-slate-400">Taxa</span><span class="text-slate-100 text-right">${Number(a.taxa_juros).toLocaleString('pt-BR', { minimumFractionDigits: 1 })}% a.m.</span>`);
                if (a.valor_parcela != null) linhas.push(`<span class="text-slate-400">Parcela (12x)</span><span class="text-slate-100 text-right">${brl(a.valor_parcela)}</span>`);
                if (a.comprometimento_renda_pct != null) linhas.push(`<span class="text-slate-400">Comprometimento</span><span class="text-slate-100 text-right">${a.comprometimento_renda_pct}%</span>`);
                if (a.motivo_rejeicao) linhas.push(`<span class="text-slate-400">Motivo</span><span class="text-red-300 text-right">${escapeHtml(a.motivo_rejeicao)}</span>`);

                return `
                    <div class="bg-slate-950/40 border border-panelBorder rounded-2xl p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border ${cor}">${rotulo}</span>
                                <span class="ml-2 text-xs text-slate-500 capitalize">${escapeHtml(a.tipo_credito ?? '')}</span>
                            </div>
                            <span class="text-xs text-slate-500">#${a.id} · ${data}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-x-4 gap-y-1.5 text-sm">${linhas.join('')}</div>
                        ${a.simulacao_url ? `<a href="${a.simulacao_url}" class="inline-block mt-3 text-sm text-emerald-400 hover:text-emerald-300 transition-all">Abrir simulação</a>` : ''}
                    </div>`;
            }

            async function abrirAnalises(cliente) {
                $('modal-nome').textContent = cliente.nome;
                $('modal-sub').textContent = `CPF ${formataCpf(cliente.cpf)}`;
                $('modal-corpo').innerHTML = '<p class="text-sm text-slate-500 py-6 text-center">Carregando…</p>';
                $('modal-analises').classList.remove('hidden');

                try {
                    const resp = await fetch(`/api/clientes/${cliente.id}`, { headers: { Accept: 'application/json' } });
                    const { data } = await resp.json();
                    const analises = data.analises ?? [];
                    $('modal-corpo').innerHTML = analises.length
                        ? analises.map(cardAnalise).join('')
                        : '<p class="text-sm text-slate-500 py-6 text-center">Este cliente ainda não possui análises.</p>';
                } catch (err) {
                    $('modal-corpo').innerHTML = '<p class="text-sm text-red-400 py-6 text-center">Falha ao carregar as análises.</p>';
                }
            }

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                esconderMsgs();

                const id = $('c-id').value;
                const payload = {
                    nome: $('c-nome').value.trim(),
                    cpf: soDigitos($('c-cpf').value),
                    email: $('c-email').value.trim(),
                    telefone: soDigitos($('c-telefone').value) || null,
                    renda_mensal: moedaParaNumero($('c-renda').value),
                };

                const url = id ? `/api/clientes/${id}` : '/api/clientes';
                const method = id ? 'PUT' : 'POST';

                const btn = $('c-submit');
                btn.disabled = true;
                btn.classList.add('opacity-60');

                try {
                    const resp = await fetch(url, {
                        method,
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify(payload),
                    });
                    const body = await resp.json().catch(() => ({}));

                    if (resp.status === 422) {
                        mostrarErros(Object.values(body.errors || { e: [body.message] }).flat());
                        return;
                    }
                    if (!resp.ok) {
                        mostrarErros([body.message || 'Erro ao salvar o cliente.']);
                        return;
                    }

                    const editou = Boolean(id);
                    sairModoEdicao();
                    mostrarOk(editou ? 'Cliente atualizado com sucesso.' : 'Cliente cadastrado com sucesso.');
                    carregar(editou ? paginaAtual : 1);
                } catch (err) {
                    mostrarErros(['Falha de conexão com o servidor.']);
                } finally {
                    btn.disabled = false;
                    btn.classList.remove('opacity-60');
                }
            });

            carregar(1);
        });
    </script>
</body>
</html>
