
                    {{-- Formulário de Detalhes da Pesquisa --}}
                    <div id="formDetalhesPesquisa" style="display: block;">
                        <form action="" method="POST">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h2 style="color:black;">Detalhes da Pesquisa</h2>
                                <button type="button" class="btn btn-info toggleFormsBtn">Arquivo de
                                    Apresentacao</button>
                            </div>
                            <div class="mb-3">
                                <label for="pesquisa" class="form-label">Nome da Pesquisa</label>
                                <input type="text" class="form-control" id="pesquisa" name="pesquisa" value=""
                                    required readonly>
                            </div>
                            <div class="mb-3">
                                <label for="resumo" class="form-label">Resumo</label>
                                <textarea class="form-control" id="resumo" maxlength="1650" name="resumo" rows="3" required readonly></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="termos" class="form-label">Termos de Indexação</label>
                                <textarea class="form-control" id="termos" name="termos" rows="3" required readonly></textarea>
                            </div>
                            <button type="button" id="editBtn" class="btn btn-primary">Editar</button>
                            <button type="submit" class="btn btn-success">Salvar Alterações</button>
                        </form>

