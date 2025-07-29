# Publishing Guide for dhiraj/php-file-manager

This guide explains how to publish and maintain the `dhiraj/php-file-manager` package on Packagist and other repositories.

## Prerequisites

- GitHub account with access to the repository
- Packagist account (https://packagist.org)
- Git configured with your credentials
- Composer installed locally

## Initial Publication to Packagist

### 1. Prepare the Repository

Ensure your repository has:
- ✅ Complete `composer.json` with proper metadata
- ✅ MIT license file
- ✅ Comprehensive README.md
- ✅ Proper semantic versioning tags
- ✅ All code following PSR-12 standards

### 2. Create a Release on GitHub

```bash
# Ensure you're on the main branch
git checkout main
git pull origin main

# Create and push a version tag
git tag -a v1.0.0 -m "Initial stable release"
git push origin v1.0.0

# Create release on GitHub
# Go to: https://github.com/dhirajdhiman/php-file-manager/releases/new
# - Tag: v1.0.0
# - Title: "v1.0.0 - Initial Release"
# - Description: Copy from CHANGELOG.md
```

### 3. Submit to Packagist

1. Visit https://packagist.org/
2. Log in with your GitHub account
3. Click "Submit Package"
4. Enter repository URL: `https://github.com/dhirajdhiman/php-file-manager`
5. Click "Check" to validate
6. Click "Submit" to publish

### 4. Enable Auto-Updates

1. Go to your package page on Packagist
2. Click "Settings"
3. Add GitHub webhook:
   - Payload URL: Copy from Packagist settings
   - Content type: `application/json`
   - Events: "Just the push event"
   - Active: ✅

## Version Management

### Semantic Versioning

Follow [Semantic Versioning](https://semver.org/):

- **MAJOR** (1.0.0 → 2.0.0): Breaking changes
- **MINOR** (1.0.0 → 1.1.0): New features, backward compatible
- **PATCH** (1.0.0 → 1.0.1): Bug fixes, backward compatible

### Release Process

#### For Patch Releases (Bug Fixes)

```bash
# Make your bug fixes
git checkout main
git pull origin main

# Update CHANGELOG.md with bug fixes
# Update version in relevant files if needed

# Commit changes
git add .
git commit -m "fix: resolve critical security issue"

# Create and push tag
git tag -a v1.0.1 -m "Bug fix release"
git push origin v1.0.1
git push origin main

# Create GitHub release with changelog
```

#### For Minor Releases (New Features)

```bash
# Develop features in feature branches
git checkout -b feature/new-functionality
# ... develop and test ...
git checkout main
git merge feature/new-functionality

# Update CHANGELOG.md
# Update documentation
# Run all tests

git add .
git commit -m "feat: add new functionality"

# Create and push tag
git tag -a v1.1.0 -m "Feature release with new functionality"
git push origin v1.1.0
git push origin main
```

#### For Major Releases (Breaking Changes)

```bash
# Thoroughly test breaking changes
# Update MIGRATION.md guide
# Update all documentation
# Update CHANGELOG.md with breaking changes

git add .
git commit -m "feat!: implement new architecture (BREAKING CHANGE)"

# Create and push tag
git tag -a v2.0.0 -m "Major release with breaking changes"
git push origin v2.0.0
git push origin main
```

## Package Maintenance

### Quality Checks Before Release

```bash
# Run all tests
composer test

# Check code style
composer phpcs

# Run static analysis
composer phpstan

# Check for security vulnerabilities
composer audit

# Validate composer.json
composer validate
```

### Documentation Updates

Before each release, ensure:
- [ ] README.md is up to date
- [ ] CHANGELOG.md includes all changes
- [ ] API documentation is current
- [ ] Examples are working
- [ ] Version numbers are consistent

### Release Checklist

- [ ] All features implemented and tested
- [ ] Documentation updated
- [ ] CHANGELOG.md updated
- [ ] Version tag created
- [ ] GitHub release created
- [ ] Packagist updated automatically
- [ ] Release announcement prepared

## Distribution Channels

### 1. Packagist (Primary)

- **URL**: https://packagist.org/packages/dhiraj/php-file-manager
- **Installation**: `composer require dhiraj/php-file-manager`
- **Updates**: Automatic via GitHub webhook

### 2. GitHub Releases

- **URL**: https://github.com/dhirajdhiman/php-file-manager/releases
- **Purpose**: Detailed release notes and downloadable archives
- **Format**: Include changelog, upgrade instructions, and breaking changes

### 3. GitHub Packages (Optional)

```bash
# Configure for GitHub Packages
composer config repositories.github composer https://composer.github.com/dhirajdhiman/
```

## Marketing and Promotion

### Release Announcements

1. **GitHub Discussions**: Post in community discussions
2. **Social Media**: Tweet about major releases
3. **PHP Communities**: Share in relevant forums/groups
4. **Documentation Sites**: Update package listings

### Package Statistics

Monitor package usage:
- Packagist download stats
- GitHub stars and forks
-GitHub traffic analytics
- Issue resolution time

## Maintenance Schedule

### Regular Tasks

- **Weekly**: Review and respond to issues
- **Bi-weekly**: Security updates and dependency checks
- **Monthly**: Performance optimization review
- **Quarterly**: Major feature planning

### Long-term Maintenance

- **PHP Version Support**: Stay current with PHP releases
- **Dependency Updates**: Keep dependencies up to date
- **Security Patches**: Apply security fixes promptly
- **Feature Requests**: Evaluate and implement popular requests

## Security Considerations

### Security Releases

For security issues:
1. **Private Fix**: Develop fix in private repository
2. **Coordinate Disclosure**: Follow responsible disclosure
3. **Emergency Release**: Fast-track release process
4. **Security Advisory**: Create GitHub security advisory
5. **User Notification**: Notify users through multiple channels

### Security Monitoring

- Monitor security advisories for dependencies
- Use automated security scanning tools
- Encourage responsible disclosure
- Maintain security contact information

## Troubleshooting

### Common Issues

**Packagist Not Updating**
- Check GitHub webhook configuration
- Verify repository permissions
- Manual update via Packagist interface

**Version Conflicts**
- Ensure proper semantic versioning
- Check dependency compatibility
- Update constraint requirements

**Installation Failures**
- Verify PHP version requirements
- Check required extensions
- Validate composer.json syntax

## Support and Community

### Support Channels

- **GitHub Issues**: Bug reports and feature requests
- **GitHub Discussions**: Community questions
- **Email**: Direct contact for security issues

### Community Building

- Respond to issues promptly
- Welcome new contributors
- Maintain clear contribution guidelines
- Recognize community contributions

## Metrics and Analytics

### Track Success Metrics

- Download counts (weekly/monthly)
- GitHub stars and forks
- Issue resolution time
- Community engagement
- User feedback scores

### Release Impact Analysis

After each release:
- Monitor download spikes
- Track issue reports
- Gather user feedback
- Assess feature adoption

## Conclusion

Successful package maintenance requires:
- Consistent release process
- Quality assurance
- Community engagement
- Prompt security responses
- Clear documentation

Following this guide ensures the `dhiraj/php-file-manager` package remains high-quality, secure, and valuable to the PHP community.

---

**Last Updated**: January 29, 2025
**Maintainer**: Dhiraj Dhiman
**Repository**: https://github.com/dhirajdhiman/php-file-manager
