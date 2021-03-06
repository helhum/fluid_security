<?php
namespace TYPO3\Fluid\Tests\Functional;

use TYPO3\CMS\Core\Tests\FunctionalTestCase;
use TYPO3\CMS\Fluid\View\StandaloneView;

class FluidTests extends FunctionalTestCase
{
    protected $testExtensionsToLoad = ['typo3conf/ext/fluid_security'];
    protected $coreExtensionsToLoad = ['fluid'];
    protected $configurationToUseInTestInstance = [
        'SYS' => [
            'caching' => [
                'cacheConfigurations' => [
                    'fluid_template' => array(
                        'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class,
                    ),
                ]
            ]
        ]
    ];

    public function viewHelperTemplateSourcesDataProvider()
    {
        return [
            [
                '<ft:myHtmlMessage message="{message}"/>',
                ['message' => 'This action was successful!'],
                '<div class="message">This action was successful!</div>'
            ],
        ];
    }

    /**
     * @param string $viewHelperTemplate
     * @param array $vars
     * @param string $expectedOutput
     * @param string $format
     *
     * @test
     * @dataProvider viewHelperTemplateSourcesDataProvider
     */
    public function renderingTest($viewHelperTemplate, array $vars, $expectedOutput, $format = 'html')
    {
        if (!in_array($format, array('html', 'json'))) {
            $format = 'html';
        }
        $view = new StandaloneView();
        $view->assignMultiple($vars);
        $view->setFormat($format);
        $viewHelperTemplate = '{namespace ft=Helhum\FluidSecurity\ViewHelpers}' . $viewHelperTemplate;
        $view->setTemplateSource($viewHelperTemplate);

        $this->assertContains($expectedOutput, $view->render());
    }
}
