# MailingBundle

```yaml
# config/packages/creonit_mailing.yaml

creonit_mailing:
    from: 'noreply@creonit.ru'
    base_template: 'mail/base.html.twig'
    templates_path: '%kernel.project_dir%/config/mailing_templates'
    globals:
        parameter: 'value'
```

```yaml
# config/mailing_templates/template.yaml

example:
    title: 'Example Template'
    from:
        email: 'noreply@creonit.ru'
        name: 'Creonit'
    subject: 'Welcome'
    template: '<div>{{ message }}</div>'
```

`custom template loader`

```php
use Creonit\MailingBundle\Templating\Loader\AbstractTemplateLoader;
use Creonit\MailingBundle\Templating\MailingTemplate;
use Creonit\MailingBundle\Templating\TemplateCollection;

class MyTemplateLoader extends AbstractTemplateLoader
{
    public function load(TemplateCollection $templateCollection)
    {
        $template = new MailingTemplate('my_template');
        $template
            ->setTitle('Example custom loader')
            ->setSubject('Example custom loader')
            ->setTemplate('<p>Custom loader</p>');
        
        $templateCollection->add($template);
    }
}
```

`custom message builder`

```php
use Creonit\MailingBundle\Message\MailingMessage;
use Creonit\MailingBundle\Message\MessageBuilderInterface;
use Creonit\MailingBundle\Templating\MailingTemplate;

class MyMessageBuilder implements MessageBuilderInterface
{
    public function build(MailingTemplate $template, array $context): MailingMessage
    {
        $message = new MailingMessage();
        $message->cc('major@gmail.com');

        return $message;
    }

    public function supports(MailingTemplate $template, array $context): bool
    {
        return $template->getKey() === 'my_template';
    }
}
```
`send message`

```php
use Creonit\MailingBundle\Mailing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;

class MailingController extends AbstractController
{
    public function sendEmail(Mailing $mailing, MailerInterface $mailer)
    {
        $email = 'example@exmple.com';
        $template = 'my_template';

        $message = $mailing->buildMessage($template, ['message' => 'Hello']);
        $message->to($email);

        $mailer->send($message);
    }
}
```