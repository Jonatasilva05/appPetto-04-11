// =======================================================
// 1. VARIÁVEIS DE TRADUÇÃO (Objeto Completo)
// =======================================================
const translations = {
    'pt': {
        // ===================================
        // A. NAVEGAÇÃO (header.php)
        // ===================================
        'nav-solucao': 'Solução',
        'nav-diferenciais': 'Diferenciais',
        'nav-carteira': 'Carteira Digital',
        'nav-funcionalidades': 'Funcionalidades',
        'nav-tecnologia': 'Tecnologia',
        'nav-login': 'LOGIN',

        // ===================================
        // B. PÁGINA INICIAL (inicio.php)
        // ===================================
        // Slides
        'slider1-h1': 'Excelência em Cuidado Veterinário Digital.',
        'slider1-p': 'Precisão, segurança e uma experiência de usuário sem precedentes.',
        'slider2-h1': 'A plataforma definitiva para profissionais e tutores.',
        'slider2-p': 'Que buscam precisão, segurança e uma experiência de usuário sem precedentes.',
        'slider3-h1': 'Precisão, segurança e uma experiência de usuário sem precedentes.',
        'slider3-p': 'O futuro do cuidado veterinário está aqui, acessível a todos.',

        // Solução
        'solution-title': 'A Solução Petto',
        'solution-subtitle': 'Redefinindo os padrões de gestão de saúde animal.',
        'solution-h3': 'Uma Plataforma Desenhada para a Perfeição.',
        'solution-p1': 'No mercado de saúde animal, a precisão é a nossa prioridade. O Petto foi meticulosamente projetado para ser mais do que uma ferramenta de registro; é um **ecossistema de dados** que garante a integridade de cada informação, desde a vacinação mais rotineira até o histórico clínico mais complexo.',
        'solution-p2': 'A arquitetura robusta da nossa plataforma se baseia em princípios de **segurança de nível corporativo** e em uma interface intuitiva que facilita a adoção por tutores e a integração por clínicas veterinárias.',

        // Por Que Escolher
        'whychoose-title': 'Por Que Escolher a Petto?',
        'whychoose-subtitle': 'Nossos valores centrais transformam a maneira como você cuida do seu pet.',
        'value1-title': 'Gestão Proativa da Saúde',
        'value1-p1': 'Não somos apenas um repositório de dados. O Petto utiliza o histórico clínico completo para oferecer **insights proativos**. Analisamos padrões e tendências para alertar tutores e veterinários sobre a necessidade de exames preventivos ou ajustes no plano de saúde, garantindo um cuidado **centrado na prevenção**.',
        'value1-p2': 'Essa abordagem preditiva garante que você esteja sempre um passo à frente, **reduzindo riscos** e promovendo uma vida mais longa e saudável para o seu animal.',
        'value1-cta': 'Ver Ferramentas de Análise',
        'value2-title': 'O Futuro do Prontuário',
        'value2-text': 'Nosso prontuário digital suporta a integração com tecnologias emergentes, como telemedicina veterinária e IA. Seu pet está pronto para o futuro da saúde animal.',
        'value3-title': 'Otimização de Custos',
        'value3-text': 'O histórico unificado evita a repetição desnecessária de exames e procedimentos, gerando economia significativa para o tutor a longo prazo.',

        // Carteira Digital
        'wallet-title': 'Carteira Digital de Vacinação',
        'wallet-subtitle': 'Autenticidade e conveniência para o cuidado do seu pet.',
        'wallet-h3': 'A Validade do Papel com a Segurança do Digital.',
        'wallet-p1': 'O Petto revoluciona a forma como você gerencia as vacinas do seu pet. Nossa carteira digital armazena todos os registros de forma **criptografada e certificada**, eliminando o risco de perda, fraude ou ilegibilidade dos documentos de papel.',
        'wallet-p2': 'Cada vacinação registrada é autenticada por clínicas parceiras, gerando um **certificado digital exclusivo**. Isso garante que o histórico de saúde do seu pet seja reconhecido em qualquer lugar, de forma instantânea e segura.',

        // Funcionalidades
        'features-title': 'Nossas Funcionalidades Chave',
        'features-subtitle': 'Ferramentas de precisão para a sua tranquilidade.',
        'feature1-title': 'Certificação Digital',
        'feature1-text': 'Emita e gerencie certificados digitais de vacinação com selo de autenticidade, eliminando falsificações e garantindo a validade de cada dose.',
        'feature2-title': 'Segurança Criptografada',
        'feature2-text': 'Seus dados e os do seu pet são protegidos com criptografia de ponta a ponta, assegurando total privacidade e conformidade com as normas de proteção de dados.',
        'feature3-title': 'Análise e Relatórios',
        'feature3-text': 'Obtenha insights detalhados sobre a saúde do seu pet. Nossa plataforma gera relatórios completos para que você e seu veterinário possam tomar decisões informadas.',
        'feature4-title': 'Sincronização em Tempo Real',
        'feature4-text': 'As informações são atualizadas instantaneamente em todos os seus dispositivos, permitindo acesso imediato ao histórico de saúde do seu pet, em qualquer lugar.',
        'feature5-title': 'Acesso para Profissionais',
        'feature5-text': 'Veterinários e clínicas parceiras têm acesso seguro ao histórico de vacinas e exames, otimizando o atendimento e garantindo um cuidado coordenado.',
        'feature6-title': 'Notificações Inteligentes',
        'feature6-text': 'Nosso sistema envia lembretes inteligentes para as próximas vacinas e consultas, garantindo que você nunca perca um compromisso importante.',
        'feature7-title': 'Consultar Meus Dados',
        'feature7-text': 'Acesse rapidamente suas informações pessoais, dados de conta e pets vinculados. Tudo organizado em um painel simples e intuitivo.',
        'feature8-title': 'Histórico Completo do Pet',
        'feature8-text': 'Veja todo o histórico de vacinas, consultas, exames e atualizações do seu pet em uma linha do tempo detalhada.',
        
        // Tecnologia (RESTITUÍDO)
        'tech-title': 'Nossa Abordagem Tecnológica',
        'tech-subtitle': 'Inovação e segurança no coração de cada funcionalidade.',
        'tech-h3': 'Precisão e Segurança em Cada Byte.',
        'tech-p1': 'O Petto é mais do que uma interface; é uma solução completa de **gestão de dados** para a saúde animal. Nossa arquitetura foi construída para processar grandes volumes de informações com rapidez, garantindo que o histórico do seu pet esteja sempre disponível e seguro.',
        'tech-p2': 'Utilizamos a mais alta tecnologia em **criptografia e integração** de dados, permitindo a comunicação fluida entre tutores e clínicas. Nossa plataforma é um motor de inovação, projetada para crescer e se adaptar, sempre com o foco na segurança e na excelência do cuidado.',


        // ===================================
        // C. LOGIN (login.php)
        // ===================================
        'login-overlay-h2': 'Bem-vindo de volta!',
        'login-overlay-p': 'Entre na sua conta para cuidar do seu pet ou oferecer serviços veterinários.',
        'login-title': 'Login',
        'login-email-label': 'E-mail:',
        'login-password-label': 'Senha:',
        'login-btn-enter': 'Entrar',
        'login-forgot-password': 'Esqueceu sua senha?',
        'login-no-account': 'Não possui conta?',
        'login-register-link': 'Cadastre-se',


        // ===================================
        // D. CADASTRO (register_tutor/vet.php)
        // ===================================
        // Tutor
        'register-tutor-h2': 'Seja um Tutor Petto!',
        'register-tutor-p': 'Crie sua conta para encontrar os melhores serviços e cuidados para seu animal de estimação.',
        'register-title-tutor': 'Cadastro Tutor',
        
        // Veterinário
        'register-vet-h2': 'Parceiro Petto Saúde!',
        'register-vet-p': 'Preencha todos os passos para completar seu cadastro profissional.',
        'register-title-vet': 'Cadastro Veterinário',
        'register-crvm-label': 'CRMV:',
        'register-area-label': 'Área de Atuação:',
        'register-specialty-label': 'Especialização:',

        // Comuns
        'register-link-tutor': 'Tutor',
        'register-link-vet': 'Veterinário',
        'register-name-label': 'Nome:',
        'register-email-label': 'Email:',
        'register-password-label': 'Senha:',
        'register-phone-label': 'Telefone:',
        'register-address-label': 'Endereço:',
        'register-profile-photo-label': 'Foto de Perfil:',
        'register-first-pet-label': 'Nome do primeiro pet:',
        'register-favorite-color-label': 'Cor favorita:',
        'register-btn-continue': 'Continuar',
        'register-btn-back': 'Voltar',
        'register-btn-register': 'CADASTRAR',
        'register-already-have-account': 'Já possui conta?',
        'register-login-link': 'Faça Login',


        // ===================================
        // E. RODAPÉ (footer.php)
        // ===================================
        'footer-title1': 'Petto',
        'footer-p1': 'A vanguarda da saúde e do bem-estar animal. Uma solução premium, meticulosamente desenvolvida para sua total confiança.',
        'footer-title2': 'Navegação',
        'footer-nav1': 'A Solução',
        'footer-nav2': 'Diferenciais',
        'footer-nav-carteira': 'Carteira Digital',
        'footer-nav4': 'Funcionalidades',
        'footer-nav3': 'Tecnologia',
        'footer-nav7': 'Acesso',
        'footer-title3': 'Contato',
        'footer-contact1': 'Email: contato@petto.com',
        'footer-contact2': 'Telefone: (11) 99876-5432',
        'footer-contact3': 'Rua da Inovação, 123 - São Paulo',
        'footer-title4': 'Redes Sociais',
        'footer-bottom-text': '2025 Petto. Todos os direitos reservados.',
    },
    'en': {
        // ... (versão em inglês omitida para brevidade, mas deve conter todas as chaves correspondentes)
        // ===================================
        // A. NAVEGAÇÃO (header.php)
        // ===================================
        'nav-solucao': 'Solution',
        'nav-diferenciais': 'Features',
        'nav-carteira': 'Digital Wallet',
        'nav-funcionalidades': 'Functionality',
        'nav-tecnologia': 'Technology',
        'nav-login': 'LOGIN',

        // ===================================
        // B. PÁGINA INICIAL (inicio.php)
        // ===================================
        'slider1-h1': 'Excellence in Digital Veterinary Care.',
        'slider1-p': 'Precision, security, and an unprecedented user experience.',
        'slider2-h1': 'The definitive platform for professionals and tutors.',
        'slider2-p': 'Who seek precision, security, and an unprecedented user experience.',
        'slider3-h1': 'Precision, security, and an unprecedented user experience.',
        'slider3-p': 'The future of veterinary care is here, accessible to all.',
        'solution-title': 'The Petto Solution',
        'solution-subtitle': 'Redefining the standards of animal health management.',
        'solution-h3': 'A Platform Designed for Perfection.',
        'solution-p1': 'In the animal health market, precision is our priority. Petto was meticulously designed to be more than a registration tool; it is a **data ecosystem** that guarantees the integrity of every piece of information, from the most routine vaccination to the most complex clinical history.',
        'solution-p2': 'The robust architecture of our platform is based on **corporate-level security** principles and an intuitive interface that facilitates adoption by tutors and integration by veterinary clinics.',
        'whychoose-title': 'Why Choose Petto?',
        'whychoose-subtitle': 'Our core values transform the way you care for your pet.',
        'value1-title': 'Proactive Health Management',
        'value1-p1': 'We are not just a data repository. Petto uses the complete clinical history to offer **proactive insights**. We analyze patterns and trends to alert tutors and veterinarians about the need for preventive exams or adjustments to the health plan, ensuring **prevention-focused care**.',
        'value1-p2': 'This predictive approach ensures you are always one step ahead, **reducing risks** and promoting a longer, healthier life for your animal.',
        'value1-cta': 'View Analysis Tools',
        'value2-title': 'The Future of the Medical Record',
        'value2-text': 'Our digital medical record supports integration with emerging technologies, such as veterinary telemedicine and AI. Your pet is ready for the future of animal health.',
        'value3-title': 'Cost Optimization',
        'value3-text': 'The unified history avoids unnecessary repetition of exams and procedures, generating significant savings for the tutor in the long term.',
        'wallet-title': 'Digital Vaccination Wallet',
        'wallet-subtitle': 'Authenticity and convenience for your pet\'s care.',
        'wallet-h3': 'The Validity of Paper with the Security of Digital.',
        'wallet-p1': 'Petto revolutionizes the way you manage your pet\'s vaccines. Our digital wallet stores all records in an **encrypted and certified** manner, eliminating the risk of loss, fraud, or illegibility of paper documents.',
        'wallet-p2': 'Each registered vaccination is authenticated by partner clinics, generating an **exclusive digital certificate**. This ensures that your pet\'s health history is recognized anywhere, instantly and securely.',
        'features-title': 'Our Key Features',
        'features-subtitle': 'Precision tools for your peace of mind.',
        'feature1-title': 'Digital Certification',
        'feature1-text': 'Issue and manage digital vaccination certificates with an authenticity seal, eliminating counterfeits and guaranteeing the validity of each dose.',
        'feature2-title': 'Encrypted Security',
        'feature2-text': 'Your data and your pet\'s data are protected with end-to-end encryption, ensuring total privacy and compliance with data protection standards.',
        'feature3-title': 'Analysis and Reports',
        'feature3-text': 'Get detailed insights into your pet\'s health. Our platform generates complete reports so you and your veterinarian can make informed decisions.',
        'feature4-title': 'Real-Time Synchronization',
        'feature4-text': 'Information is instantly updated on all your devices, allowing immediate access to your pet\'s health history, anywhere.',
        'feature5-title': 'Access for Professionals',
        'feature5-text': 'Partner veterinarians and clinics have secure access to vaccine and exam history, optimizing care and ensuring coordinated care.',
        'feature6-title': 'Smart Notifications',
        'feature6-text': 'Our system sends smart reminders for upcoming vaccines and appointments, ensuring you never miss an important commitment.',
        'feature7-title': 'Consult My Data',
        'feature7-text': 'Quickly access your personal information, account details, and linked pets. Everything organized in a simple and intuitive panel.',
        'feature8-title': 'Complete Pet History',
        'feature8-text': 'View your pet\'s entire history of vaccines, appointments, exams, and updates in a detailed timeline.',
        
        // Tecnologia (RESTITUÍDO)
        'tech-title': 'Our Technology Approach',
        'tech-subtitle': 'Innovation and security at the heart of every functionality.',
        'tech-h3': 'Precision and Security in Every Byte.',
        'tech-p1': 'Petto is more than an interface; it is a complete **data management** solution for animal health. Our architecture was built to process large volumes of information quickly, ensuring that your pet\'s history is always available and secure.',
        'tech-p2': 'We use the highest technology in **encryption and data integration**, allowing fluid communication between tutors and clinics. Our platform is a motor of innovation, designed to grow and adapt, always focusing on security and excellence in care.',

        // ===================================
        // C. LOGIN (login.php)
        // ===================================
        'login-overlay-h2': 'Welcome back!',
        'login-overlay-p': 'Log in to your account to take care of your pet or offer veterinary services.',
        'login-title': 'Login',
        'login-email-label': 'Email:',
        'login-password-label': 'Password:',
        'login-btn-enter': 'Log In',
        'login-forgot-password': 'Forgot your password?',
        'login-no-account': 'Don\'t have an account?',
        'login-register-link': 'Sign Up',

        // ===================================
        // D. CADASTRO (register_tutor/vet.php)
        // ===================================
        // Tutor
        'register-tutor-h2': 'Be a Petto Tutor!',
        'register-tutor-p': 'Create your account to find the best services and care for your pet.',
        'register-title-tutor': 'Tutor Registration',
        
        // Veterinário
        'register-vet-h2': 'Petto Health Partner!',
        'register-vet-p': 'Fill in all steps to complete your professional registration.',
        'register-title-vet': 'Registration',
        'register-crvm-label': 'CRMV:',
        'register-area-label': 'Area of Expertise:',
        'register-specialty-label': 'Specialization:',

        // Comuns
        'register-link-tutor': 'Tutor',
        'register-link-vet': 'Veterinarian',
        'register-name-label': 'Name:',
        'register-email-label': 'Email:',
        'register-password-label': 'Password:',
        'register-phone-label': 'Phone:',
        'register-address-label': 'Address:',
        'register-profile-photo-label': 'Profile Photo:',
        'register-first-pet-label': 'First pet\'s name:',
        'register-favorite-color-label': 'Favorite color:',
        'register-btn-continue': 'Continue',
        'register-btn-back': 'Back',
        'register-btn-register': 'REGISTER',
        'register-already-have-account': 'Already have an account?',
        'register-login-link': 'Log In',
        

        // ===================================
        // E. RODAPÉ (footer.php)
        // ===================================
        'footer-title1': 'Petto',
        'footer-p1': 'The vanguard of animal health and well-being. A premium solution, meticulously developed for your total confidence.',
        'footer-title2': 'Navigation',
        'footer-nav1': 'The Solution',
        'footer-nav2': 'Key Differences',
        'footer-nav-carteira': 'Digital Wallet',
        'footer-nav4': 'Functionality',
        'footer-nav3': 'Technology',
        'footer-nav7': 'Access',
        'footer-title3': 'Contact',
        'footer-contact1': 'Email: contato@petto.com',
        'footer-contact2': 'Phone: (11) 99876-5432',
        'footer-contact3': 'Innovation Street, 123 - São Paulo',
        'footer-title4': 'Social Media',
        'footer-bottom-text': '2025 Petto. All rights reserved.',
    }
};

// =======================================================
// 2. FUNÇÃO applyLanguage (Única e Essencial)
// =======================================================
function applyLanguage(lang) {
    // 1. Salva a preferência no LocalStorage (persistência)
    localStorage.setItem('language', lang);
    
    // 2. Aplica as traduções
    document.querySelectorAll('[data-lang-key]').forEach(element => {
        const key = element.getAttribute('data-lang-key');
        
        if (translations[lang] && translations[lang][key]) {
            const translation = translations[lang][key];

            // Condição para input/placeholder
            if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
                if (element.placeholder !== undefined) {
                    element.placeholder = translation;
                }
            } else {
                // Para todos os outros elementos (h2, p, label, button, a)
                element.innerHTML = translation; 
            }
        }
    });
    
    // 3. Atualiza o seletor de idioma (Header)
    document.querySelectorAll('.lang-option').forEach(option => {
        option.classList.remove('active-lang');
        if (option.getAttribute('data-lang') === lang) {
            option.classList.add('active-lang');
        }
    });

    // 4. Atualiza o atributo lang da tag <html>
    document.documentElement.lang = lang;
}


// =======================================================
// 3. INICIALIZAÇÃO ÚNICA (DOMContentLoaded)
// =======================================================
document.addEventListener('DOMContentLoaded', () => {
    
    // 3.1. Funcionalidade do Menu Mobile
    const menuToggle = document.querySelector('.menu-toggle');
    const navMenu = document.querySelector('.menu-nav-sofisticado');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });
    }
    
    // 3.2. Inicializa o Idioma e configura listeners
    const savedLang = localStorage.getItem('language') || 'pt';
    applyLanguage(savedLang);
    
    // Configura listeners de idioma
    document.querySelectorAll('.lang-option').forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault(); 
            const newLang = button.getAttribute('data-lang');
            applyLanguage(newLang);
        });
    });
});