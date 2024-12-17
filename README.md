# Documentação do Sistema de Gerenciamento de Cardápio e Playlists de Mídias

## Visão Geral

Este sistema permite a gestão de um cardápio dinâmico que exibe uma lista de produtos e suas promoções, além de possibilitar a criação e reprodução de playlists de mídias em dispositivos conectados. As informações podem ser atualizadas remotamente, garantindo que os dispositivos exibam sempre os dados mais recentes.

## Requisitos Funcionais

### R01 - Cardápio

- **Lista de Produtos e Promoções**:
  - O sistema deve exibir uma lista de produtos com seus respectivos valores.
  - Os valores dos produtos devem ser atualizáveis remotamente, permitindo alterações em tempo real.

- **Mídias Disponíveis**:
  - Todas as mídias devem ser acessíveis online, permitindo que dispositivos conectados possam reproduzi-las.
  - As mídias obrigatórias incluem:
    - **Imagens**: Suporte a arquivos e links para imagens nos formatos JPG e PNG.
    - **Vídeos**: Suporte a arquivos ou links para vídeos.

### R02 - Sobre as Playlists

- **Geração de Playlists**:
  - O sistema deve permitir a criação de playlists de mídias que podem ser reproduzidas em dispositivos específicos.
  
- **Reprodução em Dispositivos**:
  - Uma playlist pode ser reproduzida em um ou mais dispositivos simultaneamente.
  - Deve ser possível selecionar qual playlist será reproduzida em cada dispositivo.

### R03 - Funcionalidade do Sistema

- **Servidor Online**:
  - O sistema deve possuir um servidor online para configuração das informações, incluindo mídias, playlists e dispositivos.
  
- **Atualização Automática**:
  - Quando as informações de um dispositivo forem alteradas, o sistema deve ser atualizado automaticamente, sem necessidade de intervenção manual no local.

## Arquitetura do Sistema

### Componentes Principais

1. **Frontend**:
   - Interface do usuário para visualização do cardápio, gerenciamento de mídias e playlists.
   - Responsável pela interação com os dispositivos e pela exibição das informações.

2. **Backend**:
   - Servidor que gerencia as informações do cardápio, mídias e playlists.
   - Responsável por processar as requisições do frontend e atualizar os dados conforme necessário.

3. **Banco de Dados**:
   - Armazena informações sobre produtos, preços, mídias, playlists e dispositivos.
   - Permite consultas e atualizações em tempo real.

4. **Dispositivos**:
   - Dispositivos conectados que reproduzem as playlists e exibem o cardápio.
   - Recebem atualizações do servidor automaticamente.

## Funcionalidades

### 1. Gerenciamento de Cardápio

- **Adicionar/Remover Produtos**: Permite que administradores adicionem ou removam produtos do cardápio.
- **Atualizar Preços**: Os preços dos produtos podem ser alterados remotamente através do backend.
- **Exibição de Promoções**: O sistema deve destacar promoções e ofertas especiais.

### 2. Gerenciamento de Mídias

- **Upload de Mídias**: Permite o upload de imagens e vídeos para o servidor.
- **Links para Mídias**: Suporte para adicionar links externos para vídeos e imagens.

### 3. Criação e Gerenciamento de Playlists

- **Criar Playlists**: Administradores podem criar playlists personalizadas com as mídias disponíveis.
- **Reprodução em Dispositivos**: Seleção de playlists para reprodução em dispositivos específicos.
- **Reprodução Simultânea**: Capacidade de reproduzir a mesma playlist em múltiplos dispositivos.

## Conclusão

Este sistema de gerenciamento de cardápio e playlists de mídias oferece uma solução robusta para a exibição de produtos e promoções em dispositivos conectados. Com a capacidade de atualizar informações remotamente e reproduzir mídias de forma flexível, ele atende às necessidades de negócios que buscam modernizar sua apresentação de produtos e promoções.
