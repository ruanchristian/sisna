
# 🎓 Sistema de Seleção dos Novos Alunos - EEEP Dr. José Alves da Silveira

Este projeto é um sistema web desenvolvido em **PHP** com o objetivo de gerenciar o processo interno de **seleção de novos alunos** da **Escola Dr. José Alves da Silveira**. 

Ele permite o cadastro de processos seletivos anuais, cursos, alunos e a geração de resultados com a classificação automatizada.

---

## 📌 Funcionalidades Principais

- ✅ Cadastro e gestão de **processos seletivos** por ano
- ✅ Gestão de **cursos** com definição de vagas por categoria (PCD, pública ampla concorrência, etc.)
- ✅ Registro de **alunos inscritos**, com notas e categoria escolar
- ✅ Controle de **configurações especiais de vagas + ordem de desempate**
- ✅ **Classificação automática** por curso e categoria, seguindo os critérios de desempate setados para aquele respectivo processo seletivo
- ✅ Geração dos **resultados preliminar/final** (PDF)

---

## 🧱 Estrutura do Banco de Dados

O sistema possui as seguintes tabelas principais:

| Tabela               | Finalidade                                     | Relacionamentos entre tabelas                                 |
|----------------------|-----------------------------------------------|-------------------------------------------------------|
| **users**            | Usuários do sistema (admin/monitor)           | -                                                     |
| **courses**          | Cursos disponíveis                            | 1:N com `students` e `results`                        |
| **selective_processes** | Processos seletivos anuais                 | 1:N com `students` e `results`, 1:1 com `special_configs` |
| **students**         | Alunos inscritos                              | N:1 com `courses` e `selective_processes`, 1:1 com `results` |
| **results**          | Resultado final dos alunos                   | N:1 com `students`, `courses` e `selective_processes` |
| **special_configs**  | Configuração de vagas por categoria          | 1:1 com `selective_processes`                         |

### 📋 Campos principais por tabela:

#### users
- id (PK)
- name
- email
- type (enum: administrador/monitor)
- password
- timestamps (horário de criação e atualização)

#### courses
- id (PK)
- nome (único)
- cor_curso (cor HEX)

#### selective_processes
- id (PK)
- ano (único)
- estado (bool)
- cursos (ex: 1-2-3-4)

#### students
- id (PK)
- nome
- data_nascimento
- curso_id (FK)
- processo_id (FK)
- media_pt, media_mt, media_final
- origem (enum: PCD, PUBLICA_AMPLA e etc...)

#### results
- id (PK)
- student_id (FK)
- process_id (FK)
- course_id (FK)
- is_classified (bool)
- origin (enum)

#### special_configs
- id (PK)
- vagas_pcd, vagas_publica_ampla, vagas_publica_prox, vagas_private_ampla, vagas_private_prox
- ordem_desempate (json)
- processo_id (FK)

---

---

## 🚀 Tecnologias Utilizadas

- **PHP 8.x**
- **Laravel 9.x**
- **MySQL**
- **Bootstrap** (Front-end básico)
- **Admin LTE** (Interface mais amigável)
- **DomPDF** (Geração de PDFs)

---

## Imagens

![Home](https://postimg.cc/CndCct5J)
![Cursos](https://postimg.cc/23X71z9t)
![Processos](https://postimg.cc/NKR6CRGh)
![Critérios](https://postimg.cc/XpRKgsbc)

---

## 🛠️ Instalação Local

### Pré-requisitos

- PHP >= 8.1
- Composer
- MySQL

### Passo a Passo

1. **Clone o projeto**

```bash
git clone https://github.com/ruanchristian/sisna.git
cd sisna
```

2. **Instale as dependências PHP**

```bash
composer install
```

3. **Configure o ambiente**

```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure o banco de dados no arquivo `.env`**

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_seu_banco
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

5. **Execute as migrations**

```bash
php artisan migrate
```

6. **Configurando o Usuário Administrador Inicial**

Para acessar o sistema, é necessário criar o primeiro usuário administrador:

- Abra o arquivo:  
`database/seeders/AdminSeeder.php`

- Edite as credenciais padrão (nome, e-mail, senha hash).

- Depois execute:

```bash
php artisan db:seed --class=AdminSeeder
```

Assim será criado o usuário administrador inicial.

7. **Rode o servidor de desenvolvimento**

```bash
php artisan serve
```

Agora o sistema está pronto pra ser acessado em: [http://localhost:8000](http://localhost:8000)

---
<span>
    <b>Desenvolvido por Ruan Christian &copy; | 2023<b>
</span>  
