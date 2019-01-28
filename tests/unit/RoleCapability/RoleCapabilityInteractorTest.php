<?php

use Faker\Factory as Faker;
use Plugins\User\RoleCapability\Interactor;
use Plugins\User\RoleCapability\ConfigRepository;
use Aksara\Support\Strings;
use Aksara\Support\Arrays;

class RoleCapabilityInteractorTest extends PHPUnit\Framework\TestCase
{
    private $faker;
    private $configRepo;
    private $configData;

    protected function setup()
    {
        $this->faker = Faker::create();

        $this->configData = [];

        $this->configRepo = $this->getMockBuilder(ConfigRepository::class)
            ->getMock();

        $this->configRepo->expects($this->any())
            ->method('get')
            ->with('aksara.user.capabilities')
            ->willReturnCallback(function ($configName, $default) {
                if (is_null($this->configData)) {
                    return $default;
                }
                return $this->configData;
            });

        $this->configRepo->expects($this->any())
            ->method('set')
            ->with('aksara.user.capabilities')
            ->willReturnCallback(function ($configName, $setValue) {
                $this->configData = $setValue;
            });
    }

    /** @test */
    public function shouldAddCapability()
    {
        $name = $this->faker->word;
        $name2 = $this->faker->word;

        $strHelper = $this->getMockBuilder(Strings::class)
            ->disableOriginalConstructor()
            ->getMock();

        $strHelper->expects($this->any())
            ->method('slug')
            ->with($this->logicalOr($name, $name2))
            ->willReturnCallback(function ($name) {
                return $name;
            });

        $arrayHelper = $this->getMockBuilder(Arrays::class)
            ->disableOriginalConstructor()
            ->getMock();

        $arrayHelper->expects($this->any())
            ->method('searchValueRecursive')
            ->with($name)
            ->willReturnCallback(function ($name) {
                return $this->configData[$name];
            });

        $interactor = new Interactor(
            $this->configRepo,
            $strHelper,
            $arrayHelper
        );

        $this->assertTrue($interactor->add($name));

        $expected = [
            $name => [
                'name' => $name,
                'capabilities' => [],
            ]
        ];

        $this->assertEquals($expected, $this->configData);

        $this->assertTrue($interactor->add($name2, $name2, $name));

        $expected2 = [
            $name => [
                'name' => $name,
                'capabilities' => [
                    $name2 => [
                        'name' => $name2,
                    ],
                ],
            ]
        ];

        $this->assertEquals($expected2, $this->configData);

        $this->assertEquals($this->configData[$name], $interactor->get($name));

        $this->assertEquals($this->configData, $interactor->all());
    }
}
